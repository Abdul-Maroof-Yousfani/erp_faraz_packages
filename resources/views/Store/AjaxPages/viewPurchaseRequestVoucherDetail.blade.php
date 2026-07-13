<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;

$id = $_GET['id'] ?? null;
$m = Session::get('run_company');
$approve = ReuseableCode::check_rights(16);
$EmailPrintSetting = $_GET['EmailPrintSetting'] ?? 1;

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
    .po-view-shell {
        background: #f4f6f8;
        margin: -15px;
        padding: 18px;
    }

    .po-view-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .po-view-toolbar-left,
    .po-view-toolbar-right {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .po-view-email {
        min-width: 260px;
    }

    .po-view-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .po-print-only {
        display: none;
    }

    .po-view-card {
        background: #fff;
        border: 1px solid #d7dde4;
        border-radius: 10px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .po-view-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e8edf2;
    }

    .po-view-topbar {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: flex-start;
        justify-content: space-between;
    }

    .po-view-brand {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .po-view-brand-copy {
        font-size: 13px;
        line-height: 1.6;
        color: #425466;
    }

    .po-view-brand-copy strong {
        display: block;
        font-size: 18px;
        color: #0f172a;
        line-height: 1.3;
    }

    .po-view-title {
        text-align: right;
        min-width: 260px;
    }

    .po-view-title h2 {
        margin: 0 0 10px;
        font-size: 28px;
        letter-spacing: 1px;
        color: #0f172a;
    }

    .po-view-meta {
        width: 100%;
        max-width: 320px;
        margin-left: auto;
        border-collapse: collapse;
        font-size: 12px;
    }

    .po-view-meta td {
        border: 1px solid #d6dde6;
        padding: 6px 8px;
    }

    .po-view-meta td:first-child {
        width: 42%;
        background: #eef4fb;
        font-weight: 700;
        color: #243447;
    }

    .po-view-body {
        padding: 22px;
    }

    .po-view-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 18px;
    }

    .po-view-panel {
        border: 1px solid #d6dde6;
        border-radius: 8px;
        overflow: hidden;
        height: 100%;
    }

    .po-view-panel-title {
        background: #2f6fad;
        color: #fff;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .po-view-panel-body {
        padding: 14px;
        font-size: 13px;
        line-height: 1.7;
        color: #334155;
        min-height: 150px;
    }

    .po-view-panel-body strong {
        color: #0f172a;
    }

    .po-view-table-wrap {
        overflow-x: auto;
    }

    .po-view-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .po-view-table th,
    .po-view-table td {
        border: 1px solid #d6dde6;
        padding: 8px 7px;
        vertical-align: top;
    }

    .po-view-table th {
        background: #eef4fb;
        color: #1e293b;
        text-align: center;
        font-weight: 700;
        white-space: nowrap;
    }

    .po-view-table td.text-center {
        text-align: center;
    }

    .po-view-table td.text-right {
        text-align: right;
    }

    .po-view-note {
        margin-top: 16px;
        padding: 14px 16px;
        border: 1px solid #d6dde6;
        border-radius: 8px;
        background: #fafcfe;
        color: #334155;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-line;
    }

    .po-view-note strong {
        color: #0f172a;
    }

    .po-view-signatures {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-top: 26px;
    }

    .po-view-signature {
        padding-top: 10px;
        border-top: 1px solid #94a3b8;
        text-align: center;
        font-size: 12px;
        color: #475569;
    }

    .po-view-signature strong {
        display: block;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .po-print-page {
        width: 155mm;
        max-width: 155mm;
        margin: 0 auto;
    }

    .po-print-card {
        background: #fff;
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

    @media (max-width: 991px) {
        .po-view-grid,
        .po-view-signatures {
            grid-template-columns: 1fr;
        }

        .po-view-title {
            text-align: left;
            min-width: 0;
        }

        .po-view-meta {
            margin-left: 0;
        }
    }

    @media print {
        body * {
            visibility: hidden !important;
        }

        #showDetailModelOneParamerter,
        #showDetailModelOneParamerter * {
            visibility: visible !important;
        }

        #showDetailModelOneParamerter {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            overflow: visible !important;
        }

        #showDetailModelOneParamerter .modal-dialog {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #showDetailModelOneParamerter .modal-content,
        #showDetailModelOneParamerter .modal-body {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            background: #fff !important;
        }

        #showDetailModelOneParamerter .modal-header,
        #showDetailModelOneParamerter .modal-footer,
        #showDetailModelOneParamerter .close,
        #showDetailModelOneParamerter .printHide,
        .modal-backdrop {
            display: none !important;
        }

        .po-view-shell,
        .po-view-page,
        .po-view-card {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            box-shadow: none !important;
            border: none !important;
            max-width: 100% !important;
        }

        .po-screen-only {
            display: none !important;
        }

        .po-print-only {
            display: block !important;
        }

        .po-print-page {
            width: 155mm !important;
            max-width: 155mm !important;
            margin: 0 auto !important;
        }
    }
</style>

<div class="po-view-shell">
    @foreach ($purchaseRequestDetail as $row)
        <?php
        $supplier = CommonHelper::getSupplierDetail($row->supplier_id);
        $supplier = is_object($supplier) ? $supplier : (object) [
            'name' => '',
            'address' => '',
            'mobile_no' => '',
            'contact_person' => '',
            'ntn' => '',
            'no_of_days' => '',
        ];

        $currencyName = CommonHelper::get_curreny_name($row->currency_id);
        $currencyCode = 'PKR';
        if ((int) $row->currency_id === 4) {
            $currencyCode = 'USD';
        } elseif (!empty($currencyName)) {
            $currencyCode = strtoupper(substr(strip_tags($currencyName), 0, 3));
        }

        $termsLabel = (string) $row->terms_of_paym;
        if ((int) $row->terms_of_paym === 1) {
            $termsLabel = 'Advance';
        } elseif ((int) $row->terms_of_paym === 2) {
            $termsLabel = 'Against Delivery';
        } elseif ((int) $row->terms_of_paym === 3) {
            $termsLabel = !empty($row->no_of_days) ? $row->no_of_days . ' Days' : 'Credit';
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
        ?>

        <div class="po-view-page po-screen-only">
            <div class="po-view-toolbar printHide">
                <div class="po-view-toolbar-left"></div>
                <div class="po-view-toolbar-right">
                    @if ($approve == true)
                        {!! StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m, $row->purchase_request_status, $row->status, $row->id, 'purchase_request_no', 'purchase_request_status', 'status', 'purchase_request', 'purchase_request_data') !!}
                    @endif
                    @if ($approve == true && ($row->purchase_request_status == 1 || $row->purchase_request_status == 2))
                        <button class="delete-modal btn btn-danger-2" data-dismiss="modal" aria-hidden="true" onclick="RejectPo('{{ $row->id }}')">
                            Reject
                        </button>
                    @endif
                    <button type="button" class="btn btn-primary" onclick="printPurchaseOrderInModal()">
                        <span class="glyphicon glyphicon-print"></span> Print
                    </button>
                </div>
            </div>

            <div class="po-view-card" id="po_detail">
                <div class="po-view-header">
                    <div class="po-view-topbar">
                        <div class="po-view-brand">
                            <div>
                                {!! CommonHelper::get_company_logo(Session::get('run_company')) !!}
                            </div>
                            <div class="po-view-brand-copy">
                                <strong>{{ $companyName }}</strong>
                                <div>{{ $companyAddress }}</div>
                                <div><strong style="display:inline; font-size:13px;">Phone:</strong> {{ $companyPhone }}</div>
                                <div><strong style="display:inline; font-size:13px;">Email:</strong> {{ $companyEmail }}</div>
                            </div>
                        </div>

                        <div class="po-view-title">
                            <h2>PURCHASE ORDER</h2>
                            <table class="po-view-meta">
                                <tr>
                                    <td>PO #</td>
                                    <td>{{ strtoupper($row->purchase_request_no) }}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>{{ CommonHelper::changeDateFormat($row->purchase_request_date) }}</td>
                                </tr>
                                <tr>
                                    <td>Quotation Ref</td>
                                    <td>{{ strtoupper($quotation_ref->ref_no ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <td>Due Date</td>
                                    <td>{{ CommonHelper::changeDateFormat($row->due_date) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="po-view-body">
                    <div class="po-view-grid">
                        <div class="po-view-panel">
                            <div class="po-view-panel-title">Vendor</div>
                            <div class="po-view-panel-body">
                                <div><strong>{{ $supplier->name }}</strong></div>
                                <div>{{ $supplier->address }}</div>
                                <div><strong>Phone:</strong> {{ $supplier->mobile_no ?: '-' }}</div>
                                <div><strong>Representative:</strong> {{ $supplier->contact_person ?: '-' }}</div>
                                <div><strong>NTN / STRN:</strong> {{ $supplier->ntn ?: '-' }}</div>
                            </div>
                        </div>

                        <div class="po-view-panel">
                            <div class="po-view-panel-title">Order Info</div>
                            <div class="po-view-panel-body">
                                <div><strong>Destination:</strong> FARAZ PACKAGES (F-98 S.I.T.E KARACHI.)</div>
                                <div><strong>Payment Terms:</strong> {{ $termsLabel ?: '-' }}</div>
                                <div><strong>Currency:</strong> {{ $currencyCode }}</div>
                                <div><strong>Sales Tax:</strong> {{ number_format((float) ($row->sales_tax ?? 0), 2) }}%</div>
                                <div><strong>Amount In Words:</strong> {{ $row->amount_in_words ?: '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="po-view-table-wrap">
                        <table class="po-view-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;">Item Description</th>
                                    <th>UoM</th>
                                    <th>Bags</th>
                                    <th>Qty (KG)</th>
                                    <th>Qty (LBS)</th>
                                    <th>Rate Cal. By</th>
                                    <th>U.Price</th>
                                    <th>Net Amount</th>
                                    <th>DO No.</th>
                                    <th>Godown No.</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseRequestDataDetail as $line)
                                    <?php
                                    $rateCalLabel = $line->rate_cal_by == 2 ? 'By KGS' : ($line->rate_cal_by == 3 ? 'By LBS' : 'By BAGS');
                                    $qtyLbs = $line->qty_lbs ?? round(((float) $line->purchase_approve_qty) * 2.2, 2);
                                    $warehouseName = $line->warehouse_id ? CommonHelper::get_warehouse_name($line->warehouse_id) : '-';
                                    ?>
                                    <tr>
                                        <td>{{ CommonHelper::get_item_name($line->sub_item_id) }}</td>
                                        <td class="text-center">{{ CommonHelper::get_uom_name_by_item($line->sub_item_id) }}</td>
                                        <td class="text-center">{{ $line->bags_qty }}</td>
                                        <td class="text-center">{{ number_format((float) $line->purchase_approve_qty, 2) }}</td>
                                        <td class="text-center">{{ number_format((float) $qtyLbs, 2) }}</td>
                                        <td class="text-center">{{ $rateCalLabel }}</td>
                                        <td class="text-right">{{ $currencyCode }} {{ number_format((float) $line->rate, 3) }}</td>
                                        <td class="text-right">{{ $currencyCode }} {{ number_format((float) $line->net_amount, 3) }}</td>
                                        <td class="text-center">{{ !empty($line->do_no) ? $line->do_no : '-' }}</td>
                                        <td class="text-center">{{ !empty($line->godown_no) ? $line->godown_no : '-' }}</td>
                                        <td class="text-center">{{ $warehouseName }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="7"><strong>Total</strong></td>
                                    <td class="text-right"><strong>{{ $currencyCode }} {{ number_format($total, 2) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>

                                <tr>
                                    <td colspan="7"><strong>Sales Tax: {{ number_format((float) ($row->sales_tax ?? 0), 2) }}%</strong></td>
                                    <td class="text-right"><strong>{{ $currencyCode }} {{ number_format($salesTaxAmount, 2) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>

                                <tr>
                                    <td colspan="7"><strong>Grand Total</strong></td>
                                    <td class="text-right"><strong>{{ $currencyCode }} {{ number_format($grandTotal, 2) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if (!empty($row->description))
                        <div class="po-view-note">
                            <strong>Note:</strong>
                            {{ $row->description }}
                        </div>
                    @endif

                    <div class="po-view-signatures">
                        <div class="po-view-signature">
                            <strong>Prepared By</strong>
                            <div>{{ $preparedBy ?: '--------------------' }}</div>
                        </div>
                        <div class="po-view-signature">
                            <strong>Checked By</strong>
                            <div>{{ $checkedBy ?: '--------------------' }}</div>
                        </div>
                        <div class="po-view-signature">
                            <strong>Entry By</strong>
                            <div>{{ $enteredBy ?: '--------------------' }}</div>
                        </div>
                        <div class="po-view-signature">
                            <strong>Approved By</strong>
                            <div>{{ $approvedBy ?: '--------------------' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $amountInWords = trim((string) ($row->amount_in_words ?? ''));
        if ($amountInWords === '' && $grandTotal > 0) {
            $amountInWords = ucwords(trim(CommonHelper::numberToWords((int) round($grandTotal)))) . ' Only';
        }
        ?>
        <div class="po-print-only">
            <div class="po-print-page">
                <div class="po-title">Purchase Order Format:</div>
                <div class="po-print-card">
                    <div class="po-topbar">
                        <div class="po-company">
                            <div class="po-company-name">{{ $companyName }}</div>
                            <div>{{ $companyAddress }}</div>
                            <div style="height: 10px;"></div>
                            <div><strong>Phone:</strong> {{ $companyPhone }}</div>
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
        </div>
    @endforeach
</div>

<script>
    function printPurchaseOrderInModal() {
        window.print();
    }
</script>
