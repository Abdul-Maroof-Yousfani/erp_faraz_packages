


<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$from=Input::get('fromDate');
$to=Input::get('toDate');
$acc_id=explode(',',Input::get('accountName'));
$acc_id  =$acc_id[0];

// paid to
$cost_center=Input::get('paid_to');
$tax_mode=Input::get('tax_mode','all');
$tax_filter=Input::get('tax_filter');


        if ($cost_center!=0):
        $clause='and sub_department_id="'.$cost_center.'"';
        else:
            $clause='';
        endif;

// end
$m=Input::get('m');

?>
<style>
    .hov:hover {
        background-color: yellow;
    }

</style>



<div id="">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <h3 class="hide" style="text-align: center;">Ledger Report</h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                echo ' '.'('.date('D', strtotime($x)).')';?></label>
        </div>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" >
        <?php
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $gstTaxAccountIds = DB::Connection('mysql2')->table('gst')
            ->where('status', 1)
            ->pluck('acc_id')
            ->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->values()
            ->all();

        $taxVouchers = [];
        if ($tax_mode === 'with_tax' || $tax_mode === 'non_tax') {
            $taxVoucherQuery = DB::Connection('mysql2')->table('transactions')
                ->where('status', 1)
                ->whereBetween('v_date', [$from, $to]);

            if ($tax_mode === 'with_tax') {
                if (!empty($tax_filter) && $tax_filter != '0') {
                    $taxVoucherQuery->where('acc_id', (int) $tax_filter);
                } else {
                    if (!empty($gstTaxAccountIds)) {
                        $taxVoucherQuery->whereIn('acc_id', $gstTaxAccountIds);
                    } else {
                        $taxVoucherQuery->whereRaw('1 = 0');
                    }
                }
            } else {
                if (!empty($gstTaxAccountIds)) {
                    $taxVoucherQuery->whereIn('acc_id', $gstTaxAccountIds);
                }
            }

            $taxVouchers = $taxVoucherQuery->pluck('voucher_no')->filter()->unique()->values()->all();
        }

        $quarterQuery = DB::Connection('mysql2')->table('transactions')
            ->where('acc_id', $acc_id)
            ->where('opening_bal', 0)
            ->where('status', 1)
            ->whereBetween('v_date', [$from, $to]);

        if ($cost_center!=0) {
            $quarterQuery->where('sub_department_id', $cost_center);
        }

        if ($tax_mode === 'with_tax' && !empty($taxVouchers)) {
            $quarterQuery->whereIn('voucher_no', $taxVouchers);
        }

        if ($tax_mode === 'with_tax' && empty($taxVouchers)) {
            $quarterQuery->whereRaw('1 = 0');
        }

        if ($tax_mode === 'non_tax') {
            if (!empty($taxVouchers)) {
                $quarterQuery->whereNotIn('voucher_no', $taxVouchers);
            }
        }

        $quarter = $quarterQuery->orderBy('v_date')->get();

        $quarterVoucherNos = collect($quarter)->pluck('voucher_no')->filter()->unique()->values()->all();
        $ledgerItemDetails = [];
        $purchaseVoucherPaymentTerms = [];
        if (!empty($quarterVoucherNos)) {
            $ledgerItems = DB::Connection('mysql2')->table('stock as s')
                ->join('subitem as si', 'si.id', '=', 's.sub_item_id')
                ->whereIn('s.status', [1, 3])
                ->whereIn('s.voucher_no', $quarterVoucherNos)
                ->select('s.voucher_no', 'si.sub_ic', 's.rate', DB::raw('SUM(s.qty) as qty'))
                ->groupBy('s.voucher_no', 'si.sub_ic', 's.rate')
                ->orderBy('si.sub_ic')
                ->orderBy('s.rate')
                ->get();

            foreach ($ledgerItems as $ledgerItem) {
                $ledgerItemDetails[$ledgerItem->voucher_no][] = $ledgerItem;
            }

            $purchaseVoucherRows = DB::Connection('mysql2')->table('new_purchase_voucher as npv')
                ->whereIn('npv.pv_no', $quarterVoucherNos)
                ->select('npv.pv_no', 'npv.supplier')
                ->get();

            $supplierIds = $purchaseVoucherRows->pluck('supplier')->filter()->unique()->values()->all();
            $supplierTerms = collect();
            if (!empty($supplierIds)) {
                $supplierTerms = DB::Connection('mysql2')->table('supplier')
                    ->whereIn('id', $supplierIds)
                    ->select('id', 'terms_of_payment', 'no_of_days')
                    ->get()
                    ->keyBy('id');
            }

            foreach ($purchaseVoucherRows as $purchaseVoucherRow) {
                $supplier = $supplierTerms->get($purchaseVoucherRow->supplier);
                $termLabel = '-';

                if ($supplier) {
                    $termLabel = (string) $supplier->terms_of_payment;
                    if (!empty($supplier->no_of_days)) {
                        $termLabel .= ' | No of Days: ' . $supplier->no_of_days;
                    }
                }

                $purchaseVoucherPaymentTerms[$purchaseVoucherRow->pv_no] = $termLabel;
            }
        }

        CommonHelper::reconnectMasterDatabase();
        ?>
        <thead>


<div class="container" style="border:1px solid #e0c9c9; padding:10px; font-family:Arial;width:100%;">

    <!-- Header -->
    <div class="text-center">
        <h2 style="margin:0; font-weight:bold;">Faraz Packages</h2>
        <p style="margin:0;">F-98, S.I.T.E., Karachi</p>
        <p style="margin:0; font-size:13px;">
            Tel No. 0213-2584444, 0321-2254444, Fax No. 0213-2584444,
            email: farazpackages@gmail.com
        </p>
    </div>

    <hr style="border-top:1px solid #000; margin:5px 0;">

    <!-- Ledger + Date Row -->
    <div class="row" style="font-size:13px;">
        <div class="col-xs-6">
            <b>Ledger Report of:</b>
            <?php echo CommonHelper::get_account_name($acc_id); ?>
        </div>
        <div class="col-xs-6 text-right">
            <b>From:</b> <?php echo date('d-M-Y', strtotime($from)); ?>
            <b>To:</b> <?php echo date('d-M-Y', strtotime($to)); ?>
        </div>
    </div>

    <hr style="border-top:1px solid #000; margin:5px 0;">

    <!-- Company Name -->
    <div class="text-center hide" style="font-size:18px;">
        <b>Company Name: (<?php echo FinanceHelper::getCompanyName(Session::get('run_company')); ?>)</b>
    </div>

    <!-- Account Name -->
    <div class="text-left" style="font-size:18px;">
        <b>
            Account Name:
            (<?php echo CommonHelper::get_account_code($acc_id).' --- '.CommonHelper::get_account_name($acc_id); ?>)
        </b>
    </div>

    <!-- Tax Filter -->
    <div class="text-center hide" style="font-size:15px; margin-top:5px;">
        <b>Tax Filter:</b>
        <?php
            if ($tax_mode === 'with_tax') {
                $taxName = DB::connection('mysql2')->table('gst')
                    ->where('status', 1)
                    ->where('acc_id', $tax_filter)
                    ->select('percent', 'rate')->first();

                if (!empty($tax_filter) && $tax_filter != '0') {
                    echo $taxName ? $taxName->percent . ' (' . $taxName->rate . '%)' : 'Selected Tax';
                } else {
                    echo 'All Taxes';
                }
            } elseif ($tax_mode === 'non_tax') {
                echo 'Non Tax';
            } else {
                echo 'All Taxes';
            }
        ?>
    </div>

    <!-- Date Range -->
    <div class="text-center hide" style="font-size:16px; margin-top:5px;">
        <b>
            From Date:
            (<?php echo date('d-m-Y', strtotime($from)); ?>)
            &nbsp;&nbsp;==========
            &nbsp;&nbsp;To Date:
            (<?php echo date('d-m-Y', strtotime($to)); ?>)
        </b>
    </div>

</div>

        <tr>
            <th style="width: 100px" class="text-center">Voucher No</th>
            <th style="width: 120px" class="text-center">Date</th>
            <th style="width: 220px" class="text-center">Description</th>
            <th style="width: 120px" class="text-center hide">V Type</th>
            <th style="width: 120px" class="text-center hide">Cheque No</th>
            <th style="width: 120px" class="text-center hide">Description</th>
            <th class="text-center" style="width:100px;">Debit</th>
            <th class="text-center" style="width:100px;">Credit</th>
            <th class="text-center" style="width:100px;">Balance</th>
        </tr>
        </thead>
        <tbody id="<?php // echo $member_id; ?>">
        <?php
        $acc_code=CommonHelper::get_single_row('accounts','id',$acc_id)->code;
         $level=explode('-',$acc_code)      ;
         $level=$level[0];
        $amount=CommonHelper::get_opening_ball($from,$to,$acc_id,$m,$acc_code,$clause);
        $total_debit=0;
        $total_credit=0;
        $balance=0;

        ?>
        <tr>
            <td></td>
            <td class="text-left" colspan="2">Opening Balance</td>
            <td class="text-right"><?php if ($amount>=0): echo number_format($amount,2); $balance=$amount;  endif; ?></td>
            <td class="text-right"><?php if ($amount < 0): $balance=$amount;     $amount=$amount*-1;  echo number_format($amount,2);   endif; ?></td>
            <td class="text-right">


                <?php

                if ($level==2 || $level==3 || $level==4):
                if ($balance<0):
                $balance=$balance*-1;
                    else:
                $balance=$balance*-1;
                endif;
                endif;
                ?>
                <?php if ($balance>=0): echo number_format($balance,2); else:  echo '('.number_format($balance*-1,2).')';  endif;  ?>
            </td>
        </tr>
        <?php





        foreach($quarter as $trow):
        $code=$trow->acc_code;
        $level=explode('-',$code);
        $level=$level[0];
        $debit=0;
        $credit=0;
        $description = '';

        $type='';
        $detail='';
        $PageTitle='';
        $VoucherId = '';
        if ($trow->voucher_type==1):
        $detail='fdc/viewJournalVoucherDetail';
        $PageTitle = 'View Journal Voucher Detail';

        $jvs = DB::Connection('mysql2')->table('new_jvs')->where('jv_no','=',$trow->voucher_no)->first();
        $description = $jvs->description;
        endif;

        if ($trow->voucher_type==4):
        $detail='fdc/viewPurchaseVoucherDetail';
        $PageTitle = 'View Purchase Voucher Detail';
        $type='Purchase Invoice';

        
        $pvs =DB::Connection('mysql2')->table('new_purchase_voucher as npv')
            ->where('npv.pv_no','=',$trow->voucher_no)
            ->first();
        
        $description = $pvs->description;


        endif;


        $cheque_no='';
        $ref_no='';
        $cheque_date='';
        if ($trow->voucher_type==3):
        $VNo = substr($trow->voucher_no, 0, 3);
        $type='Receipt Voucher';
        $ref_no=  DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_no',$trow->voucher_no)->select('ref_bill_no')->value('ref_bill_no');

        $description = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_no',$trow->voucher_no)->select('description')->value('description');


        $ref_no='('.$ref_no.')';
        if($VNo == 'crv')
        {
            $detail='fdc/viewCashRvDetailNew';
        }
        else
        {
            $detail='fdc/viewBankRvDetailNew';
        }

        $PageTitle = 'View Receipt Voucher Detail';
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $cheque_data = DB::Connection('mysql2')->table('rvs')->where('rv_no',$trow->voucher_no)->first();

        // $description = $cheque_data->description ?? '';

         if (isset($cheque_data->cheque_no)):
         $cheque_no=$cheque_data->cheque_no;
          else:
        $cheque_no='';
         endif;
        $cheque_date=$cheque_date;
        CommonHelper::reconnectMasterDatabase();
        endif;

    $so='';
        if ($trow->voucher_type==6  || $trow->voucher_type==8):
        $detail='sales/viewSalesTaxInvoiceDetail';
        $PageTitle = 'Invoice';
        $type='Sales Tax Invoice';
        $so_data=  DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('gi_no',$trow->voucher_no)->select('id','so_no')->first();
         $so=strtoupper($so_data->so_no);

        endif;



        if ($trow->voucher_type==18 || $trow->voucher_type==19):
            $detail='production/view_cost?order_no='.$trow->voucher_no.'&&type=1';
            $PageTitle = 'Production';
            $type='Production';


        endif;

        if ($trow->voucher_type==16 || $trow->voucher_type==17):
            $detail='production/view_plan?order_no='.$trow->voucher_no.'&&type=1';
            $PageTitle = 'Production';
            $type='Production';


        endif;



        if ($trow->voucher_type==5):
        $detail='pdc/viewPurchaseReturnDetail';

        $PageTitle = 'Purchase Return';
        $type='Purchase Return';

        endif;

        if ($trow->voucher_type==7):
        $type='Credit Note';
                endif;

        if ($trow->voucher_type==2):
        $PayType= DB::Connection('mysql2')->table('new_pv')->where('pv_no',$trow->voucher_no)->select('payment_type')->first();
                if($PayType->payment_type == 1)
                {
                    $detail='fdc/viewBankPaymentVoucherDetailInDetail';
                }
                else{$detail='fdc/viewBankPaymentVoucherDetail';}

        //$detail='fdc/viewBankPaymentVoucherDetailInDetail';
        $PageTitle = 'View Payement Voucher Detail';
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $cheque_data=DB::Connection('mysql2')->table('new_pv')->where('pv_no',$trow->voucher_no)->first();
        $description = $cheque_data->description ?? '';

        $cheque_no=$cheque_data->cheque_no;
        $cheque_date=$cheque_data->cheque_date;
        CommonHelper::reconnectMasterDatabase();
        endif;
        ?>

        <tr  title="<?php echo $trow->voucher_type ?>"  class="hov" >
            <td><?php echo strtoupper($trow->voucher_no) ?></td>
            
                <td class="text-center"> <a onclick="showDetailModelOneParamerter('<?php echo $detail?>','<?php echo 'other'.','.$trow->voucher_no;?>','<?php echo $PageTitle?>','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success"><?php echo  date_format(date_create($trow->v_date), 'd-M-Y'); ?></a></td>
                <td class="text-left" style="white-space: nowrap !important;font-size: 11px;">
                <?php
                    $itemDetails = [];

                    if (!empty($ledgerItemDetails[$trow->voucher_no])) {
                        foreach ($ledgerItemDetails[$trow->voucher_no] as $ledgerItem) {
                            $paymentTerm = $purchaseVoucherPaymentTerms[$trow->voucher_no] ?? '-';

                            $itemDetails[] = 
                                'Item Name: ' . e($ledgerItem->sub_ic) . ' | ' .
                                'Qty: ' . number_format((float) $ledgerItem->qty, 2) . ' |</br> ' .
                                'Rate: ' . number_format((float) ($ledgerItem->rate ?? 0), 2) . ' | ' .
                                'Payment Term: ' . e($paymentTerm);
                        }
                    }

                    echo  !empty($itemDetails) ? implode('<br>', $itemDetails) : $trow->particulars;
                    ?>
            </td>
            <td class="text-center hide">{{$type}}</td>
            <td class="text-left hide"><?php echo $cheque_no.'</br>';if ($cheque_date!='0000-00-00' && $cheque_date!=''): date_format(date_create($cheque_date), 'd-m-Y');endif; ?></td>
            <td class="text-left hide">
                <?php 
                echo $description."--";
                    // if($trow->voucher_type == 4):
                    // $ParticularArray = explode('--',$trow->particulars);
                    // echo strtoupper($ParticularArray[0]);
                    // else:
                    // echo $trow->particulars.' '.$so.strtoupper($ref_no);
                    // endif;
                ?>
            </td>
            <td class="text-right"><?php if($trow->debit_credit==1){ $debit=$trow->amount; echo number_format($trow->amount,2); $total_debit+=$trow->amount;} ?></td>
            <td class="text-right"><?php if($trow->debit_credit==0){ $credit=$trow->amount; echo number_format($trow->amount,2); $total_credit+=$trow->amount;} ?></td>
            <?php



            ?>
            <td class="text-right"> <?php

                  if ($level==2 || $level==3 || $level==5 || $level ==6):
                //   if ($level==2 || $level==3 || $level==4):
                    $balance=$credit-$debit+$balance;
                    else:
                $balance=$debit-$credit+$balance;
                    endif;
                if ($balance>=0):
                echo number_format($balance,2);

                else:
                echo '('.number_format($balance*-1,2).')';
                endif;
                ?></td>

        </tr>

        <?php endforeach; ?>
        <tr>
            <td class="text-center" colspan="3"><b style="font-size: large;">TOTAL</b></td>
            <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_debit,2) ?></b></td>
            <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_credit,2) ?></b></td>
            <td  class="text-center" colspan="1"><b style="font-size: large;color: #ff9999"><?php  echo  number_format($total_debit-$total_credit) ?></b></td>

        </tr>

        </tbody>
    </table>
</div>
<script>
    $(document).ready(function(e) {
        $('#print2').click(function(){
            $("div").removeClass("table-responsive");
            $("div").removeClass("well");
            $("a").removeAttr("href");
            //$("a.link_hide").contents().unwrap();
            var content = $("#content").html();
            document.body.innerHTML = content;
            //var content = document.getElementById('header').innerHTML;
            //var content2 = document.getElementById('content').innerHTML;
            window.print();
            location.reload();
        });
    });
</script>
