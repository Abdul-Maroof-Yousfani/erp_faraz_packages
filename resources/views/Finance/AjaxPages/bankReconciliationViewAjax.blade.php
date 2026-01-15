


<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$from=Input::get('fromDate');
$to=Input::get('toDate');
$acc_id=explode(',',Input::get('accountName'));
$acc_id  =$acc_id[0];
$count = 1;

// paid to
$cost_center=Input::get('paid_to');


        if ($cost_center!=0):
        $clause='and sub_department_id="'.$cost_center.'"';
        else:
            $clause='';
        endif;

// end

$total_credit = 0 ;
$total_debit  = 0 ;


?>

@extends('layouts.default')

@section('content')
<style>
    .hov:hover {
        background-color: yellow;
    }

</style>


<div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Bank Reconciliation View</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInView('loadFilterLedgerReport','','1');?>
                                    <?php // if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php // endif; ?>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loadFilterLedgerReport">
                                        <div class="row">
                                        
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Opening Balance <br> (as per Bank Statement)</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->bank_statement_balance }}"
                                                        />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Opening Balance <br> (as per ERP)</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->bank_opening_balance_erp }}"
                                                            />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Bank Closing Balance </label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->bank_closing_balance }}"

                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Opening Balance as per Company Books:</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->bank_opening_balance_erp }}"
                                                        />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Deposits in Transit:</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->deposits }}"
                                                            />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Outstanding Cheques:</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->outstanding }}"

                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Balance as per Company Books:</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->company_book_balance }}"
                                                        />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Balance as per Bank Statement:</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->bank_statement_balance }}"
                                                            />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <label for="">Difference:</label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <input 
                                                            class="form-control"
                                                            disabled
                                                            value="{{ $BankReconciliation->difference }}"

                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!-- <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"> -->
                                                <?php // echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                                    // echo ' '.'('.date('D', strtotime($x)).')';
                                                    ?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <?php // echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                                            </div>

                                        </div>
                                        <div class="row">
                                        
                                        </div>
                                        <div style="line-height:5px;">&nbsp;</div>
                                        <h4>Payment Clearance</h4>
                                        <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" >
                                            <thead>
                                                <tr>
                                                    <td colspan="9" style="background-color:#ffffff; color:black;">

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Voucher No</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Credit  Account</th>
                                                    <th class="text-center">Cr. Amount</th>
                                                    <th class="text-center">Clearance Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="<?php // echo $member_id; ?>">
                                            <?php foreach($CreditBankReconciliationData as $key => $value): ?>
                                            <tr  title="<?php echo $value->voucher_type ?>"  class="hov" >
                                                <td>
                                                    {{$count}}
                                                </td>
                                                <td>
                                                    <?php echo strtoupper($value->voucher_no) ?>
                                                </td>
                                                <td class="text-center"> 
                                                    
                                                    <a onclick="showDetailModelOneParamerter('<?php echo $value->detail?>','<?php echo 'other'.','.$value->voucher_no;?>','<?php echo $value->PageTitle?>','<?php echo $m; ?>','')" class="btn btn-xs btn-success">
                                                    
                                                        <?php echo  date_format(date_create($value->voucher_date), 'd-M-Y'); ?>
                                                    
                                                    </a>

                                                </td>
                                                <td class="">
                                                    <?php echo db::connection('mysql2')->table('accounts')->where('id',$value->account_id)->first()->name; ?>
                                                </td>
                                                <td class="">
                                                    <?php $credit=$value->credit_amount; echo number_format($value->credit_amount,2); $total_credit+=$value->credit_amount; ?>
                                                </td>
                                                <?php



                                                ?>
                                                <td class="text-right"> 

                                                    {{ $value->voucher_date }}

                                                </td>

                                            </tr>

                                            <?php $count++; endforeach; ?>
                                            <tr class="hide">
                                                <td class="text-center" colspan="5"><b style="font-size: large;">TOTAL</b></td>
                                                <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_debit,2) ?></b></td>
                                                <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_credit,2) ?></b></td>
                                                <td  class="text-center" colspan="1"><b style="font-size: large;color: #ff9999"><?php // echo  number_format($total_debit-$total_credit) ?></b></td>

                                            </tr>

                                            </tbody>
                                        </table>
                                        <div style="line-height:5px;">&nbsp;</div>

                                        <h4>Receipts</h4>
                                        <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" >
                                            <thead>
                                                <tr>
                                                    <td colspan="9" style="background-color:#ffffff; color:black;">

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Voucher No</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Debit  Account</th>
                                                    <th class="text-center">Dr. Amount</th>
                                                    <th class="text-center">Clearance Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="<?php // echo $member_id; ?>">
                                            <?php foreach($DebitBankReconciliationData as $key => $value): ?>
                                            <tr  title="<?php echo $value->voucher_type ?>"  class="hov" >
                                                <td>
                                                    {{$count}}
                                                </td>
                                                <td>
                                                    <?php echo strtoupper($value->voucher_no) ?>
                                                </td>
                                                <td class="text-center"> 
                                                    
                                                    <a onclick="showDetailModelOneParamerter('<?php echo $value->detail?>','<?php echo 'other'.','.$value->voucher_no;?>','<?php echo $value->PageTitle?>','<?php echo $m; ?>','')" class="btn btn-xs btn-success">
                                                    
                                                        <?php echo  date_format(date_create($value->voucher_date), 'd-M-Y'); ?>
                                                    
                                                    </a>

                                                </td>
                                                <td class="">
                                                    <?php echo db::connection('mysql2')->table('accounts')->where('id',$value->account_id)->first()->name; ?>
                                                </td>
                                                <td class="">
                                                    <?php $debit=$value->debit_amount; echo number_format($value->debit_amount,2); $total_credit+=$value->debit_amount; ?>
                                                </td>
                                                <?php



                                                ?>
                                                <td class="text-right"> 

                                                    {{ $value->voucher_date }}

                                                </td>

                                            </tr>

                                            <?php $count++; endforeach; ?>
                                            <tr class="hide">
                                                <td class="text-center" colspan="5"><b style="font-size: large;">TOTAL</b></td>
                                                <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_debit,2) ?></b></td>
                                                <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_credit,2) ?></b></td>
                                                <td  class="text-center" colspan="1"><b style="font-size: large;color: #ff9999"><?php // echo  number_format($total_debit-$total_credit) ?></b></td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
