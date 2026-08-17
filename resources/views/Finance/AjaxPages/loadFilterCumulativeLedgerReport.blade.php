<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

$from        = Input::get('fromDate');
$to          = Input::get('toDate');
$acc_ids = array_values(array_filter(array_map('intval', explode(',', Input::get('accountName')))));
$acc_id  = $acc_ids[0] ?? 0;
$cost_center = Input::get('paid_to');
$tax_mode    = Input::get('tax_mode', 'all');
$tax_filter  = Input::get('tax_filter');
$m           = Input::get('m');

$clause = ($cost_center != 0)
    ? 'and sub_department_id="' . $cost_center . '"'
    : '';

$supplierRow = DB::Connection('mysql2')->table('supplier')
    ->whereIn('acc_id', $acc_ids)->where('status', 1)
    ->select('name')->first();
$customerRow = DB::Connection('mysql2')->table('customers')
    ->whereIn('acc_id', $acc_ids)->where('status', 1)
    ->select('name')->first();

$partyName = $supplierRow->name ?? ($customerRow->name ?? CommonHelper::get_account_name($acc_id));
$accCodes = DB::Connection('mysql2')->table('accounts')->whereIn('id', $acc_ids)->pluck('code')->toArray();
$accCodeDisplay = !empty($accCodes) ? implode(' / ', $accCodes) : CommonHelper::get_account_code($acc_id);

// ── TAX FILTER ──────────────────────────────────────────────
$gstTaxAccountIds = DB::Connection('mysql2')->table('gst')
    ->where('status', 1)->pluck('acc_id')->filter()
    ->map(fn($id) => (int)$id)->values()->all();

$taxVouchers = [];
if ($tax_mode === 'with_tax' || $tax_mode === 'non_tax') {
    $taxVoucherQuery = DB::Connection('mysql2')->table('transactions')
        ->where('status', 1)->whereBetween('v_date', [$from, $to]);

    if ($tax_mode === 'with_tax') {
        if (!empty($tax_filter) && $tax_filter != '0') {
            $taxVoucherQuery->where('acc_id', (int) $tax_filter);
        } else {
            !empty($gstTaxAccountIds)
                ? $taxVoucherQuery->whereIn('acc_id', $gstTaxAccountIds)
                : $taxVoucherQuery->whereRaw('1 = 0');
        }
    } else {
        if (!empty($gstTaxAccountIds)) {
            $taxVoucherQuery->whereIn('acc_id', $gstTaxAccountIds);
        }
    }
    $taxVouchers = $taxVoucherQuery->pluck('voucher_no')->filter()->unique()->values()->all();
}

// ── MAIN TRANSACTIONS QUERY ──────────────────────────────────
$quarterQuery = DB::Connection('mysql2')->table('transactions')
    ->whereIn('acc_id', $acc_ids)
    ->where('opening_bal', 0)
    ->where('status', 1)
    ->whereBetween('v_date', [$from, $to]);

if ($cost_center != 0) {
    $quarterQuery->where('sub_department_id', $cost_center);
}
if ($tax_mode === 'with_tax') {
    !empty($taxVouchers)
        ? $quarterQuery->whereIn('voucher_no', $taxVouchers)
        : $quarterQuery->whereRaw('1 = 0');
}
if ($tax_mode === 'non_tax' && !empty($taxVouchers)) {
    $quarterQuery->whereNotIn('voucher_no', $taxVouchers);
}

$quarter = $quarterQuery->orderBy('v_date')->orderBy('id')->get();

$showChequeColumns = collect($quarter)->contains(
    fn($row) => in_array((int)($row->voucher_type ?? 0), [2, 3], true)
);

$quarterVoucherNos = collect($quarter)->pluck('voucher_no')->filter()->unique()->values()->all();

// ── ITEM DETAILS ─────────────────────────────────────────────
$ledgerItemDetails       = [];
$purchaseVoucherPaymentTerms = [];

if (!empty($quarterVoucherNos)) {
    // Stock items
    $ledgerItems = DB::Connection('mysql2')->table('stock as s')
        ->join('subitem as si', 'si.id', '=', 's.sub_item_id')
        ->whereIn('s.status', [1, 3])
        ->whereIn('s.voucher_no', $quarterVoucherNos)
        ->select('s.voucher_no', 'si.sub_ic', 's.rate',
            DB::raw('SUM(s.qty) as qty'), DB::raw('SUM(s.amount) as amount'))
        ->groupBy('s.voucher_no', 'si.sub_ic', 's.rate')
        ->orderBy('si.sub_ic')->orderBy('s.rate')->get();

    foreach ($ledgerItems as $li) {
        $ledgerItemDetails[$li->voucher_no][] = $li;
    }

    // Sales invoice items
    $salesItems = DB::Connection('mysql2')->table('sales_tax_invoice_data as stid')
        ->join('sales_tax_invoice as sti', 'sti.id', '=', 'stid.master_id')
        ->join('subitem as si', 'si.id', '=', 'stid.item_id')
        ->where('stid.status', 1)->where('sti.status', 1)
        ->whereIn('sti.gi_no', $quarterVoucherNos)
        ->select('sti.gi_no as voucher_no', 'si.sub_ic', 'stid.rate',
            DB::raw('SUM(stid.qty) as qty'), DB::raw('SUM(stid.amount) as amount'))
        ->groupBy('sti.gi_no', 'si.sub_ic', 'stid.rate')
        ->orderBy('si.sub_ic')->orderBy('stid.rate')->get();

    foreach ($salesItems as $si_item) {
        if (empty($ledgerItemDetails[$si_item->voucher_no])) {
            $ledgerItemDetails[$si_item->voucher_no] = [];
        }
        $exists = collect($ledgerItemDetails[$si_item->voucher_no])->contains(
            fn($e) => (string)($e->sub_ic ?? '') === (string)($si_item->sub_ic ?? '')
                   && (float)($e->rate ?? 0)   === (float)($si_item->rate ?? 0)
                   && (float)($e->qty ?? 0)    === (float)($si_item->qty ?? 0)
                   && (float)($e->amount ?? 0) === (float)($si_item->amount ?? 0)
        );
        if (!$exists) $ledgerItemDetails[$si_item->voucher_no][] = $si_item;
    }

    // Purchase voucher items
    $pvItems = DB::Connection('mysql2')->table('new_purchase_voucher_data as npvd')
        ->join('new_purchase_voucher as npv', 'npv.id', '=', 'npvd.master_id')
        ->join('subitem as si', 'si.id', '=', 'npvd.sub_item')
        ->where('npvd.staus', 1)->where('npv.status', 1)
        ->whereIn('npv.pv_no', $quarterVoucherNos)
        ->select('npv.pv_no as voucher_no', 'si.sub_ic', 'npvd.rate',
            DB::raw('SUM(npvd.qty) as qty'), DB::raw('SUM(npvd.amount) as amount'))
        ->groupBy('npv.pv_no', 'si.sub_ic', 'npvd.rate')
        ->orderBy('si.sub_ic')->orderBy('npvd.rate')->get();

    foreach ($pvItems as $pvi) {
        if (empty($ledgerItemDetails[$pvi->voucher_no])) $ledgerItemDetails[$pvi->voucher_no] = [];
        $exists = collect($ledgerItemDetails[$pvi->voucher_no])->contains(
            fn($e) => (string)($e->sub_ic ?? '') === (string)($pvi->sub_ic ?? '')
                   && (float)($e->rate ?? 0)   === (float)($pvi->rate ?? 0)
                   && (float)($e->qty ?? 0)    === (float)($pvi->qty ?? 0)
                   && (float)($e->amount ?? 0) === (float)($pvi->amount ?? 0)
        );
        if (!$exists) $ledgerItemDetails[$pvi->voucher_no][] = $pvi;
    }

    // Purchase voucher payment terms
    $pvRows = DB::Connection('mysql2')->table('new_purchase_voucher as npv')
        ->whereIn('npv.pv_no', $quarterVoucherNos)
        ->select('npv.pv_no', 'npv.supplier')->get();

    $supplierIds   = $pvRows->pluck('supplier')->filter()->unique()->values()->all();
    $supplierTerms = collect();
    if (!empty($supplierIds)) {
        $supplierTerms = DB::Connection('mysql2')->table('supplier')
            ->whereIn('id', $supplierIds)
            ->select('id', 'terms_of_payment', 'no_of_days')->get()->keyBy('id');
    }
    foreach ($pvRows as $pvRow) {
        $sup = $supplierTerms->get($pvRow->supplier);
        $termParts = [];
        if ($sup) {
            if (!empty($sup->terms_of_payment)) $termParts[] = (string)$sup->terms_of_payment;
            if (!empty($sup->no_of_days))       $termParts[] = $sup->no_of_days . ' Days';
        }
        $purchaseVoucherPaymentTerms[$pvRow->pv_no] = !empty($termParts) ? implode(' | ', $termParts) : '-';
    }
}
// No reconnectMasterDatabase needed — default connection was never switched
?>
<style>
.hov:hover { background-color: #fffde7; }
.cumulative-badge {
    display: inline-block; padding: 2px 8px; border-radius: 4px;
    font-size: 11px; font-weight: 600; white-space: nowrap;
}
.badge-purchase  { background: #fff3e0; color: #e65100; border: 1px solid #ffb74d; }
.badge-sale      { background: #e8f5e9; color: #2e7d32; border: 1px solid #81c784; }
.badge-payment   { background: #fce4ec; color: #880e4f; border: 1px solid #f48fb1; }
.badge-receipt   { background: #e3f2fd; color: #0d47a1; border: 1px solid #90caf9; }
.badge-journal   { background: #f3e5f5; color: #4a148c; border: 1px solid #ce93d8; }
.badge-production{ background: #f1f8e9; color: #33691e; border: 1px solid #aed581; }
.badge-other     { background: #eceff1; color: #37474f; border: 1px solid #b0bec5; }
.ledger-report-table .ledger-entry-link {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 108px; padding: 6px 12px; border-radius: 6px;
    border: 1px solid #18a85b; background: #eaf8f0;
    color: #137a43 !important; font-size: 12px; font-weight: 600;
    line-height: 1.2; text-decoration: none !important; white-space: nowrap;
}
.ledger-report-table .ledger-entry-link:hover { background: #d7f1e2; color: #0f6235 !important; }
</style>

<?php echo CommonHelper::headerPrintSectionInPrintView(
    Session::get('run_company'),
    'Cumulative Ledger Report',
    'From ' . CommonHelper::changeDateFormat($from) . ' To ' . CommonHelper::changeDateFormat($to)
); ?>

<div style="margin-bottom:10px;">
    <div class="row" style="font-size:13px;">
        <div class="col-xs-6">
            <b>Cumulative Ledger (Supplier &amp; Customer) :</b>
            <strong><?php echo e($partyName); ?></strong>
        </div>
        <div class="col-xs-6 text-right">
            <b>From:</b> <?php echo date('d-M-Y', strtotime($from)); ?>
            &nbsp;&nbsp;
            <b>To:</b> <?php echo date('d-M-Y', strtotime($to)); ?>
        </div>
    </div>
    <div class="text-center" style="font-size:16px; margin-top:6px;">
        <b>
            <?php echo e($accCodeDisplay) . ' &mdash; ' . e($partyName); ?>
        </b>
        &nbsp;
        <span class="cumulative-badge badge-purchase">As Supplier</span>
        &nbsp;
        <span class="cumulative-badge badge-sale">As Customer</span>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered sf-table-th sf-table-list ledger-report-table sf-report-print-table" id="table_export1">
        <?php
        // get_opening_ball queries DB::table('company') on default (master) connection first,
        // so we must NOT switch to company DB before calling it.
        $amount = 0;
        foreach ($acc_ids as $s_acc_id) {
            $s_acc_code = DB::Connection('mysql2')->table('accounts')->where('id', $s_acc_id)->value('code') ?? '';
            $amount += CommonHelper::get_opening_ball($from, $to, $s_acc_id, $m, $s_acc_code, $clause);
        }
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $total_debit  = 0;
        $total_credit = 0;
        $balance      = 0;
        ?>
        <thead>
        <tr>
            <th style="width:110px" class="text-center">Voucher No</th>
            <th style="width:120px" class="text-center">Date</th>
            <th style="width:110px" class="text-center">Type</th>
            <th class="text-center">Description</th>
            <?php if ($showChequeColumns): ?>
            <th style="width:130px" class="text-center">Cheque No</th>
            <th style="width:110px" class="text-center">Cheque Date</th>
            <?php endif; ?>
            <th style="width:110px" class="text-center">Debit</th>
            <th style="width:110px" class="text-center">Credit</th>
            <th style="width:120px" class="text-center">Balance</th>
        </tr>
        </thead>
        <tbody>
        <!-- Opening Balance Row -->
        <tr>
            <td></td>
            <td class="text-center">-</td>
            <td class="text-center"><span class="cumulative-badge badge-other">Opening</span></td>
            <td class="text-left"><b>Opening Balance</b></td>
            <?php if ($showChequeColumns): ?>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <?php endif; ?>
            <td class="text-right"><?php if ($amount >= 0) { echo number_format($amount, 2); $balance = $amount; } ?></td>
            <td class="text-right"><?php if ($amount < 0)  { $balance = $amount; echo number_format(abs($amount), 2); } ?></td>
            <td class="text-right">
                <?php echo number_format(abs($balance), 2) . ($balance < 0 ? ' Cr' : ($balance > 0 ? ' Dr' : '')); ?>
            </td>
        </tr>

        <?php foreach ($quarter as $trow):
            $debit       = 0;
            $credit      = 0;
            $description = '';
            $cheque_no   = '';
            $cheque_date = '';
            $detail      = '';
            $PageTitle   = '';
            $typeBadge   = '';
            $typeLabel   = '';

            // ── Determine voucher type details ────────────────────
            switch ((int)$trow->voucher_type) {

                case 1: // Journal Voucher
                    $detail     = 'fdc/viewJournalVoucherDetail';
                    $PageTitle  = 'View Journal Voucher Detail';
                    $typeLabel  = 'Journal';
                    $typeBadge  = 'badge-journal';
                    $jvs        = DB::Connection('mysql2')->table('new_jvs')->where('jv_no', $trow->voucher_no)->first();
                    $description = $jvs->description ?? '';
                    break;

                case 2: // Bank Payment Voucher
                    $detail    = 'fdc/viewBankPaymentVoucherDetail';
                    $PageTitle = 'View Payment Voucher Detail';
                    $typeLabel = 'Payment (Bank)';
                    $typeBadge = 'badge-payment';
                    CommonHelper::companyDatabaseConnection($_GET['m']);
                    $pv_data   = DB::Connection('mysql2')->table('new_pv')->where('pv_no', $trow->voucher_no)->first();
                    $description = $pv_data->description ?? '';
                    $cheque_no   = $pv_data->cheque_no   ?? '';
                    $cheque_date = $pv_data->cheque_date  ?? '';
                    CommonHelper::reconnectMasterDatabase();
                    break;

                case 3: // Receipt Voucher
                    $VNo       = substr($trow->voucher_no, 0, 3);
                    $typeLabel = 'Receipt';
                    $typeBadge = 'badge-receipt';
                    $detail    = ($VNo === 'crv') ? 'fdc/viewCashRvDetailNew' : 'fdc/viewBankRvDetailNew';
                    $PageTitle = 'View Receipt Voucher Detail';
                    $ref_no    = DB::Connection('mysql2')->table('new_rvs')->where('status', 1)->where('rv_no', $trow->voucher_no)->value('ref_bill_no');
                    $description = DB::Connection('mysql2')->table('new_rvs')->where('status', 1)->where('rv_no', $trow->voucher_no)->value('description') ?? '';
                    CommonHelper::companyDatabaseConnection($_GET['m']);
                    $cheque_data = DB::Connection('mysql2')->table('rvs')->where('rv_no', $trow->voucher_no)->first();
                    $cheque_no   = $cheque_data->cheque_no   ?? '';
                    $cheque_date = $cheque_data->cheque_date ?? '';
                    CommonHelper::reconnectMasterDatabase();
                    break;

                case 4: // Purchase Invoice
                    $detail    = 'fdc/viewPurchaseVoucherDetail';
                    $PageTitle = 'View Purchase Voucher Detail';
                    $typeLabel = 'Purchase Inv.';
                    $typeBadge = 'badge-purchase';
                    $pvs       = DB::Connection('mysql2')->table('new_purchase_voucher as npv')->where('npv.pv_no', $trow->voucher_no)->first();
                    $description = $pvs->description ?? '';
                    break;

                case 5: // Purchase Return
                    $detail    = 'pdc/viewPurchaseReturnDetail';
                    $PageTitle = 'Purchase Return';
                    $typeLabel = 'Purchase Ret.';
                    $typeBadge = 'badge-purchase';
                    break;

                case 6:
                case 8: // Sales Tax Invoice
                    $detail    = 'sales/viewSalesTaxInvoiceDetail';
                    $PageTitle = 'Invoice';
                    $typeLabel = 'Sales Inv.';
                    $typeBadge = 'badge-sale';
                    $so_data   = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status', 1)->where('gi_no', $trow->voucher_no)->select('id', 'so_no')->first();
                    $description = $so_data->so_no ?? '';
                    break;

                case 7: // Credit Note
                    $detail    = 'sales/viewCreditNoteDetail';
                    $PageTitle = 'Credit Note Detail';
                    $typeLabel = 'Credit Note';
                    $typeBadge = 'badge-sale';
                    break;

                case 16:
                case 17: // Production Plan
                    $detail    = 'production/view_plan?order_no=' . $trow->voucher_no . '&&type=1';
                    $PageTitle = 'Production';
                    $typeLabel = 'Production';
                    $typeBadge = 'badge-production';
                    break;

                case 18:
                case 19: // Production Cost
                    $detail    = 'production/view_cost?order_no=' . $trow->voucher_no . '&&type=1';
                    $PageTitle = 'Production';
                    $typeLabel = 'Production';
                    $typeBadge = 'badge-production';
                    break;

                default:
                    $typeLabel = 'Other';
                    $typeBadge = 'badge-other';
            }
        ?>
        <tr class="hov" title="V-Type: <?php echo $trow->voucher_type ?>">
            <td><?php echo strtoupper($trow->voucher_no) ?></td>
            <td class="text-center">
                <a onclick="showDetailModelOneParamerter('<?php echo $detail?>', '<?php echo 'other,' . $trow->voucher_no ?>', '<?php echo $PageTitle ?>', '<?php echo $_GET['m'] ?>', '')"
                   class="ledger-entry-link">
                    <?php echo date_format(date_create($trow->v_date), 'd-M-Y'); ?>
                </a>
            </td>
            <td class="text-center">
                <span class="cumulative-badge <?php echo $typeBadge ?>"><?php echo $typeLabel ?></span>
            </td>
            <td class="text-left" style="white-space:nowrap;font-size:11px;">
                <?php
                $itemDetails = [];
                if (!empty($ledgerItemDetails[$trow->voucher_no])) {
                    foreach ($ledgerItemDetails[$trow->voucher_no] as $li) {
                        $paymentTerm = trim((string)($purchaseVoucherPaymentTerms[$trow->voucher_no] ?? ''));
                        $qty    = (float)($li->qty    ?? 0);
                        $rate   = (float)($li->rate   ?? 0);
                        $amt    = isset($li->amount) ? (float)$li->amount : ($qty * $rate);
                        $parts  = [
                            e($li->sub_ic ?? ''),
                            number_format($qty, 2) . ' KGS @ ' . number_format($rate, 2),
                            number_format($amt, 2),
                        ];
                        if ($paymentTerm !== '' && $paymentTerm !== '-') $parts[] = e($paymentTerm);
                        $itemDetails[] = implode(', ', $parts);
                    }
                }
                if (!empty($itemDetails)) {
                    echo implode('<br>', $itemDetails);
                } elseif (!empty($description)) {
                    echo e($description);
                } else {
                    echo $trow->particulars ?? '';
                }
                ?>
            </td>
            <?php if ($showChequeColumns): ?>
            <td class="text-left">
                <?php echo (in_array((int)$trow->voucher_type, [2, 3], true) && $cheque_no !== '') ? e($cheque_no) : '-'; ?>
            </td>
            <td class="text-center">
                <?php
                if (in_array((int)$trow->voucher_type, [2, 3], true) && !empty($cheque_date) && $cheque_date !== '0000-00-00') {
                    echo date_format(date_create($cheque_date), 'd-M-Y');
                } else { echo '-'; }
                ?>
            </td>
            <?php endif; ?>
            <td class="text-right">
                <?php if ($trow->debit_credit == 1) { $debit = $trow->amount; echo number_format($trow->amount, 2); $total_debit += $trow->amount; } ?>
            </td>
            <td class="text-right">
                <?php if ($trow->debit_credit == 0) { $credit = $trow->amount; echo number_format($trow->amount, 2); $total_credit += $trow->amount; } ?>
            </td>
            <td class="text-right">
                <?php
                $balance = $balance + $debit - $credit;
                echo number_format(abs($balance), 2) . ($balance < 0 ? ' Cr' : ($balance > 0 ? ' Dr' : ''));
                ?>
            </td>
        </tr>
        <?php endforeach; ?>

        <!-- Totals Row -->
        <tr style="background:#f5f5f5;">
            <td class="text-center" colspan="<?php echo $showChequeColumns ? 6 : 4; ?>">
                <b style="font-size:large;">TOTAL</b>
            </td>
            <td class="text-right">
                <b style="font-size:large;"><?php echo number_format($total_debit, 2) ?></b>
            </td>
            <td class="text-right">
                <b style="font-size:large;"><?php echo number_format($total_credit, 2) ?></b>
            </td>
            <td class="text-center">
                <b style="font-size:large; color:<?php echo $balance < 0 ? '#e53935' : '#2e7d32' ?>;">
                    <?php echo number_format(abs($balance), 2) . ($balance < 0 ? ' Cr' : ($balance > 0 ? ' Dr' : '')) ?>
                </b>
            </td>
        </tr>

        <!-- Net Position Summary -->
        <tr>
            <td colspan="<?php echo $showChequeColumns ? 9 : 7; ?>" class="text-center" style="padding:10px;">
                <?php if ($balance > 0): ?>
                    <span style="font-size:14px; font-weight:600; color:#2e7d32;">
                        ✔ Net Receivable (Party owes you): <strong><?php echo number_format(abs($balance), 2) ?></strong>
                    </span>
                <?php elseif ($balance < 0): ?>
                    <span style="font-size:14px; font-weight:600; color:#e53935;">
                        ✔ Net Payable (You owe party): <strong><?php echo number_format(abs($balance), 2) ?></strong>
                    </span>
                <?php else: ?>
                    <span style="font-size:14px; font-weight:600; color:#555;">
                        ✔ Account Settled — Zero Balance
                    </span>
                <?php endif; ?>
            </td>
        </tr>

        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#print2').click(function(){
        $("div").removeClass("table-responsive well");
        $("a").removeAttr("href");
        var content = $("#content").html();
        document.body.innerHTML = content;
        window.print();
        location.reload();
    });
});
</script>
