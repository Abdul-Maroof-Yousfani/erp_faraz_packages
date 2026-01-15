<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$count = 1;
?>

<form action="{{route('finance.addCashFlowHeadInTransaction')}}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="row">
    <div class="col-md-06">
        <label for="">Cash Flow Head</label>
        <select class="form-control" name="cash_flow_head_id" id="" required>
            <option value="">Select Cash Flow Head</option>
            @foreach($cashFlowHead as $key => $value)
                <option value="{{$value->id}}">{{$value->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<h2> Debit </h2>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>

            <th>
                <input type="checkbox" onclick="checkedAll(event)" class="debitAll" id="debit_check_all" value="dr">
                
            </th>
            <th class="text-center">S.No</th>
            <th class="text-center">Cash Flow Head</th>
            <th class="text-center">Voucher No</th>
            <th class="text-center">Voucher Date</th>
            <th class="text-center">Voucher Type</th>
            <th class="text-center">Amount</th>
        </thead>
        <tbody id="Debit_data">
        @foreach($transactions as $key => $value)


            @php

                $detail = '';
                $PageTitle = '';
                $type = '';
                $description = '';


                if ($value->voucher_type==1):
                    $detail='fdc/viewJournalVoucherDetail';
                    $PageTitle = 'View Journal Voucher Detail';
                    $type ='Journal Voucher';

                    $jvs = DB::Connection('mysql2')->table('new_jvs')->where('jv_no','=',$value->voucher_no)->first();
                    $description = $jvs->description;
                endif;

                if ($value->voucher_type==2):
                    $PayType= DB::Connection('mysql2')->table('new_pv')->where('pv_no',$value->voucher_no)->select('payment_type')->first();
                            if($PayType->payment_type == 1)
                            {
                                $detail='fdc/viewBankPaymentVoucherDetailInDetail';
                            }
                            else
                            {
                                $detail='fdc/viewBankPaymentVoucherDetail';
                            }

                    $PageTitle = 'View Payement Voucher Detail';
                    CommonHelper::companyDatabaseConnection($company_id);

                    $cheque_data=DB::Connection('mysql2')->table('new_pv')->where('pv_no',$value->voucher_no)->first();
                    $description = $cheque_data->description ?? '';

                    $cheque_no=$cheque_data->cheque_no;
                    CommonHelper::reconnectMasterDatabase();
                    $type ='Payment Voucher';

                endif;

                if ($value->voucher_type==3):
                    $VNo = substr($value->voucher_no, 0, 3);
                    $type='Receipt Voucher';
                    $description = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_no',$value->voucher_no)->select('description')->value('description');

                    if($VNo == 'crv')
                    {
                        $detail='fdc/viewCashRvDetailNew';
                    }
                    else
                    {
                        $detail='fdc/viewBankRvDetailNew';
                    }

                    $PageTitle = 'View Receipt Voucher Detail';
                    CommonHelper::companyDatabaseConnection($company_id);

                    $cheque_data = DB::Connection('mysql2')->table('rvs')->where('rv_no',$value->voucher_no)->first();

                    // $description = $cheque_data->description ?? '';

                    if (isset($cheque_data->cheque_no)):
                        $cheque_no=$cheque_data->cheque_no;
                    else:
                        $cheque_no='';
                    endif;
                    CommonHelper::reconnectMasterDatabase();
                endif;

                if ($value->voucher_type==4):
                    $detail='fdc/viewPurchaseVoucherDetail';
                    $PageTitle = 'View Purchase Voucher Detail';
                    $type='Purchase Invoice';

                    
                    $pvs =DB::Connection('mysql2')->table('new_purchase_voucher as npv')
                        ->where('npv.pv_no','=',$value->voucher_no)
                        ->first();
                    
                    $description = $pvs->description;
                endif;

                if ($value->voucher_type==5):

                    $detail='pdc/viewPurchaseReturnDetail';
                    $PageTitle = 'Purchase Return';
                    $type='Purchase Return';
                
                endif;

                if ($value->voucher_type==7):
                    $type='Credit Note';
                endif;

                if ($value->voucher_type==6  || $value->voucher_type==8):
                    $detail='sales/viewSalesTaxInvoiceDetail';
                    $PageTitle = 'Invoice';
                    $type='Sales Tax Invoice';
                    $so_data=  DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('gi_no',$value->voucher_no)->select('id','so_no')->first();
                    $so=strtoupper($so_data->so_no);

                endif;



                if ($value->voucher_type==18 || $value->voucher_type==19):
                    $detail='production/view_cost?order_no='.$value->voucher_no.'&&type=1';
                    $PageTitle = 'Production';
                    $type='Production';
                endif;

                if ($value->voucher_type==16 || $value->voucher_type==17):
                    $detail='production/view_plan?order_no='.$value->voucher_no.'&&type=1';
                    $PageTitle = 'Production';
                    $type='Production';
                endif;

                $cashFlowHead = Commonhelper::getCashFlowHead($value->cash_flow_head_id);
                $cashFlowHeadName = $cashFlowHead->name ?? '';

             
            @endphp
            @if($value->debit_credit == 1)
                <tr>
                    <td>
                        <input type="hidden" name="transaction_id[]" value="{{ $value->id }}">
                        <input type="checkbox" class="debit"  onclick="checkedSingleRow(event)" value="{{ $value->id }}">
                        <input type="hidden" name="transaction_check_{{ $value->id }}" id="transaction_check_{{ $value->id }}" value="0">
                        <input type="hidden" name="debit_credit_{{ $value->id }}" value="{{ $value->debit_credit }}">
                        <input type="hidden" name="voucher_type_{{ $value->id }}" value="{{ $value->voucher_type }}">
                    </td>
                    <td class="text-center">{{ $count++ }}</td>
                    <td class="text-center">{{ $cashFlowHeadName}}</td>
                    <td class="text-center">
                        {{ $value->voucher_no }}
                        <input type="hidden" name="voucher_no_{{ $value->id }}" value="{{ $value->voucher_no }}">
                    
                    </td>
                    <td class="text-center">
                        <a onclick="showDetailModelOneParamerter('<?php echo $detail?>','<?php echo 'other'.','.$value->voucher_no;?>','<?php echo $PageTitle?>','<?php echo $company_id?>','')" class="btn btn-xs btn-success">
                            <?php echo  date_format(date_create($value->v_date), 'd-M-Y'); ?>
                        </a>
                        <input type="hidden" name="v_date_{{ $value->id }}" value="{{ $value->v_date }}">
                    </td>
                    <td class="text-center">
                        {{ $type }}

                    </td>
                    <td class="text-center">
                        {{ $value->amount }}
                        <input type="hidden" name="amount_{{ $value->id }}" value="{{ $value->amount }}">

                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<h2> Credit </h2>
@php
$count = 1;
@endphp
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
        <th>
                <input type="checkbox" onclick="checkedAll(event)" class="creditAll" id="credit_check_all" value="cr">
                
            </th>
            <th class="text-center">S.No</th>
            <th class="text-center">Cash Flow Head</th>
            <th class="text-center">Voucher No</th>
            <th class="text-center">Voucher Date</th>
            <th class="text-center">Voucher Type</th>
            <th class="text-center">Amount</th>
        </thead>
        <tbody id="Credit_data">
            @foreach($transactions as $key => $value)


                @php
                
                    $detail = '';
                    $PageTitle = ''; 
                    $type = ''; 
                    $description = '';           


                    if ($value->voucher_type==1):
                        $detail='fdc/viewJournalVoucherDetail';
                        $PageTitle = 'View Journal Voucher Detail';
                        $type ='Journal Voucher';
                        $jvs = DB::Connection('mysql2')->table('new_jvs')->where('jv_no','=',$value->voucher_no)->first();
                        $description = $jvs->description;
                    endif;

                    if ($value->voucher_type==2):
                        $PayType= DB::Connection('mysql2')->table('new_pv')->where('pv_no',$value->voucher_no)->select('payment_type')->first();
                                if($PayType->payment_type == 1)
                                {
                                    $detail='fdc/viewBankPaymentVoucherDetailInDetail';
                                }
                                else
                                {
                                    $detail='fdc/viewBankPaymentVoucherDetail';
                                }

                        $PageTitle = 'View Payement Voucher Detail';
                        CommonHelper::companyDatabaseConnection($company_id);

                        $cheque_data=DB::Connection('mysql2')->table('new_pv')->where('pv_no',$value->voucher_no)->first();
                        $description = $cheque_data->description ?? '';

                        $cheque_no=$cheque_data->cheque_no;
                        CommonHelper::reconnectMasterDatabase();
                        $type ='Payment Voucher';
                    endif;

                    if ($value->voucher_type==3):
                        $VNo = substr($value->voucher_no, 0, 3);
                        $type='Receipt Voucher';
                        $description = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_no',$value->voucher_no)->select('description')->value('description');

                        if($VNo == 'crv')
                        {
                            $detail='fdc/viewCashRvDetailNew';
                        }
                        else
                        {
                            $detail='fdc/viewBankRvDetailNew';
                        }

                        $PageTitle = 'View Receipt Voucher Detail';
                        CommonHelper::companyDatabaseConnection($company_id);

                        $cheque_data = DB::Connection('mysql2')->table('rvs')->where('rv_no',$value->voucher_no)->first();

                        // $description = $cheque_data->description ?? '';

                        if (isset($cheque_data->cheque_no)):
                            $cheque_no=$cheque_data->cheque_no;
                        else:
                            $cheque_no='';
                        endif;
                        CommonHelper::reconnectMasterDatabase();
                    endif;

                    if ($value->voucher_type==4):
                        $detail='fdc/viewPurchaseVoucherDetail';
                        $PageTitle = 'View Purchase Voucher Detail';
                        $type='Purchase Invoice';

                        
                        $pvs =DB::Connection('mysql2')->table('new_purchase_voucher as npv')
                            ->where('npv.pv_no','=',$value->voucher_no)
                            ->first();
                        
                        $description = $pvs->description;
                    endif;

                    if ($value->voucher_type==5):

                        $detail='pdc/viewPurchaseReturnDetail';
                        $PageTitle = 'Purchase Return';
                        $type='Purchase Return';
                    
                    endif;

                    if ($value->voucher_type==7):
                        $type='Credit Note';
                    endif;

                    if ($value->voucher_type==6  || $value->voucher_type==8):
                        $detail='sales/viewSalesTaxInvoiceDetail';
                        $PageTitle = 'Invoice';
                        $type='Sales Tax Invoice';
                        $so_data=  DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->where('gi_no',$value->voucher_no)->select('id','so_no')->first();
                        $so=strtoupper($so_data->so_no);

                    endif;



                    if ($value->voucher_type==18 || $value->voucher_type==19):
                        $detail='production/view_cost?order_no='.$value->voucher_no.'&&type=1';
                        $PageTitle = 'Production';
                        $type='Production';
                    endif;

                    if ($value->voucher_type==16 || $value->voucher_type==17):
                        $detail='production/view_plan?order_no='.$value->voucher_no.'&&type=1';
                        $PageTitle = 'Production';
                        $type='Production';
                    endif;

                    $cashFlowHead = Commonhelper::getCashFlowHead($value->cash_flow_head_id);
                    $cashFlowHeadName = $cashFlowHead->name ?? '';


                @endphp
                @if($value->debit_credit == 0)
                    <tr>
                        <td>
                            <input type="hidden" name="transaction_id[]" value="{{ $value->id }}">
                            <input type="checkbox" onclick="checkedSingleRow(event)" class="credit" value="{{ $value->id }}">
                            <input type="hidden" name="transaction_check_{{ $value->id }}" id="transaction_check_{{ $value->id }}" value="0">
                            <input type="hidden" name="debit_credit_{{ $value->id }}" value="{{ $value->debit_credit }}">
                            <input type="hidden" name="voucher_type_{{ $value->id }}" value="{{ $value->voucher_type }}">
                        </td>
                        <td class="text-center">{{ $count++ }}</td>
                        <td class="text-center">{{ $cashFlowHeadName }}</td>
                        <td class="text-center">
                            {{ $value->voucher_no }}
                            <input type="hidden" name="voucher_no_{{ $value->id }}" value="{{ $value->voucher_no }}">
                        
                        </td>
                        <td class="text-center">
                            <a onclick="showDetailModelOneParamerter('<?php echo $detail?>','<?php echo 'other'.','.$value->voucher_no;?>','<?php echo $PageTitle?>','<?php echo $company_id?>','')" class="btn btn-xs btn-success">
                                <?php echo  date_format(date_create($value->v_date), 'd-M-Y'); ?>
                            </a>
                            <input type="hidden" name="v_date_{{ $value->id }}" value="{{ $value->v_date }}">
                        </td>
                        <td class="text-center">
                        {{ $type }}

                        </td>
                        <td class="text-center">
                            {{ $value->amount }}
                            <input type="hidden" name="amount_{{ $value->id }}" value="{{ $value->amount }}">

                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary mr-1">Add</button>
</div>
</form>

<script>

function checkedAll(e) 
{
    let debit = document.querySelectorAll('.debit')
    let credit = document.querySelectorAll('.credit')
    let element = e.target;

    if(element.checked)
    {
        if(element.value == 'dr')
        {
            debit.forEach( (e,index) => {
                e.checked = true;
                document.querySelector('#transaction_check_'+e.value).value = 1
            });
        }
        if(element.value == 'cr')
        {
            credit.forEach( (e,index) => {
                e.checked = true;
                document.querySelector('#transaction_check_'+e.value).value = 1

            });
        }
    }
    else
    {
        if(element.value == 'dr')
        {
            debit.forEach( (e,index) => {
                e.checked = false;
                document.querySelector('#transaction_check_'+e.value).value = 0

            });
        }
        if(element.value == 'cr')
        {
            credit.forEach( (e,index) => {
                e.checked = false;
                document.querySelector('#transaction_check_'+e.value).value = 0
            });
        }

    }

}

function checkedSingleRow(e) 
{
    let element = e.target;
    if(element.checked)
    {
        document.querySelector('#transaction_check_'+element.value).value = 1;
    }
    else
    {
        document.querySelector('#transaction_check_'+element.value).value = 0;
    }
}

</script>