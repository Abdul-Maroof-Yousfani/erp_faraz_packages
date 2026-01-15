


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
$m=Input::get('m');

?>
<style>
    .hov:hover {
        background-color: yellow;
    }

</style>



<div id="">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="">Opening Balance <br> (as per Bank Statement)</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <input 
                    class="form-control"
                    type="number"
                    id="bank_opening_custom"
                    step="any"
                    onkeyup="calculation()"
                    value="0"
                    />
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="">Opening Balance <br> (as per ERP)</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <input 
                        class="form-control"
                        type="number"
                        id="bank_opening_erp"
                        name="bank_opening_balance_erp"
                        step="any"
                        readonly
                        />
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="">Bank Closing Balance </label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <input 
                    class="form-control"
                    type="number"
                    id="bank_closing_custom"
                    name="bank_closing_balance"
                    step="any"
                    onkeyup="calculation()"
                    value="0"

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
    <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" >
        <?php
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $quarter = DB::select("SELECT * from  transactions
  											WHERE acc_id = ".$acc_id." and opening_bal=0  AND status=1 $clause AND v_date
  											 between '".$from."' and '".$to."'  ORDER BY v_date");

        CommonHelper::reconnectMasterDatabase();
        ?>
        <thead>



        <tr>
            <td colspan="9" style="background-color:#ffffff; color:black;">

            </td>
        </tr>
    


        <tr>
            <th class="text-center"></th>
            <th class="text-center">S.No</th>
            <th class="text-center">Voucher No</th>
            <th class="text-center">Date</th>
            <th class="text-center">Debit Account</th>
            <th class="text-center">Dr. Amount </th>
            <th class="text-center">Credit  Account</th>
            <th class="text-center">Cr. Amount</th>
            <th class="text-center">Clearance Date</th>
        </tr>
        </thead>
        <tbody id="<?php // echo $member_id; ?>">
        <?php
            $acc_code = CommonHelper::get_single_row('accounts','id',$acc_id)->code;
            $level = explode('-',$acc_code)      ;
            $level = $level[0];
            $amount = CommonHelper::get_opening_ball($from,$to,$acc_id,$m,$acc_code,$clause);
            $total_debit = 0;
            $total_credit = 0;
            $balance = 0;

        ?>
        <tr class="hide">
            <td></td>
            <td></td>
            <td class="text-left" colspan="3">Opening Balance</td>
            <td class="text-right"><?php if ($amount>=0): echo number_format($amount,2) ; $balance=$amount;  endif; ?></td>
            <td class="text-right"><?php if ($amount < 0): $balance=$amount;     $amount=$amount*-1;  echo number_format($amount,2);   endif; ?>
            
            <input type="hidden" id="erp_amount" value="<?php echo $amount; ?>">
            </td>
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

            // echo "<pre>";
            // print_r($quarter);
            // exit();
        $code=$trow->acc_code;
        $level=explode('-',$code);
        $level=$level[0];
        $debit=0;
        $credit=0;

        $type='';
        $detail='';
        $PageTitle='';
        $VoucherId = '';
        if ($trow->voucher_type==1):
            $detail='fdc/viewJournalVoucherDetail';
            $PageTitle = 'View Journal Voucher Detail';
        endif;

        if ($trow->voucher_type==4):
            $detail='fdc/viewPurchaseVoucherDetail';
            $PageTitle = 'View Purchase Voucher Detail';
            $type='Purchase Invoice';
        endif;


        $cheque_no='';
        $ref_no='';
        $cheque_date='';
        if ($trow->voucher_type==3):
            $VNo = substr($trow->voucher_no, 0, 3);
            $type='Receipt Voucher';
            $ref_no=  DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_no',$trow->voucher_no)->select('ref_bill_no')->value('ref_bill_no');
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

        $cheque_data=DB::table('rvs')->where('rv_no',$trow->voucher_no)->first();

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

        $cheque_data=DB::table('new_pv')->where('pv_no',$trow->voucher_no)->first();
        $cheque_no=$cheque_data->cheque_no;
        $cheque_date=$cheque_data->cheque_date;
        CommonHelper::reconnectMasterDatabase();
        endif;
        ?>

        <tr  title="<?php echo $trow->voucher_type ?>"  class="hov" >
            <td>
                <input type="checkbox" onclick="checkRow(event)" id="checkbox{{$count}}" class="checkbox" value="{{$count}}">
                @if($trow->debit_credit == 1)
                <input type="hidden" id="type{{$count}}" name="amount_type{{$count}}" value="dr">
                @elseif($trow->debit_credit == 0)
                <input type="hidden" id="type{{$count}}" name="amount_type{{$count}}" value="cr">
                @endif
            </td>
            <td>
                {{$count}}
            </td>
            <td>
                <?php echo strtoupper($trow->voucher_no) ?>
            </td>
            <td class="text-center"> 
                <a onclick="showDetailModelOneParamerter('<?php echo $detail?>','<?php echo 'other'.','.$trow->voucher_no;?>','<?php echo $PageTitle?>','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success"><?php echo  date_format(date_create($trow->v_date), 'd-M-Y'); ?></a>
                <input type="hidden" id="voucher_no{{$count}}" name="voucher_no{{$count}}" value="{{$trow->voucher_no}}" />     
                <input type="hidden" id="detail{{$count}}" name="detail{{$count}}" value="{{$detail}}" />     
                <input type="hidden" id="PageTitle{{$count}}" name="PageTitle{{$count}}" value="{{$PageTitle}}" />     
                <input type="hidden" id="voucher_type{{$count}}" name="voucher_type{{$count}}" value="{{$trow->voucher_type}}" />     
                <input type="hidden" id="company_id" name="company_id" value="<?php echo $_GET['m']?>" />
                <input type="hidden" name="count[]" value="{{$count}}" />

            </td>
            <td class="">
                <?php if($trow->debit_credit==1){ echo db::connection('mysql2')->table('accounts')->where('id',$trow->acc_id)->first()->name; } ?>
            </td>
            <td class="">
                <?php if($trow->debit_credit==1){ $debit=$trow->amount; echo number_format($trow->amount,2); $total_debit+=$trow->amount;} ?>
                
                <input type="hidden" id="debit_amount{{$count}}" name="debit_amount{{$count}}"
                @if($trow->debit_credit==1) 
                  value="<?php echo $trow->amount; ?>"
                @else
                  value="0"
                @endif
                  /> 

            </td>
            <td class="">
                <?php if($trow->debit_credit==0){ echo db::connection('mysql2')->table('accounts')->where('id',$trow->acc_id)->first()->name; } ?>
            </td>
            <td class="">
                <?php if($trow->debit_credit==0){ $credit=$trow->amount; echo number_format($trow->amount,2); $total_credit+=$trow->amount;} ?>
                <input type="hidden" id="credit_amount{{$count}}" name="credit_amount{{$count}}" 
                @if($trow->debit_credit==0)
                    value="<?php echo $trow->amount; ?>"
                @else
                    value="0"
                @endif
                    /> 
            </td>
            <?php



            ?>
            <td class="text-right"> 

                <input type="hidden" id="check_type{{$count}}" name="check_type{{$count}}" value="0" />     
                <input type="date" class="form-control" readonly id="cl_date{{$count}}" name="voucher_date{{$count}}" data-value="{{$count}}" value="{{$trow->v_date}}" onchange="calculationBydate(event)" />

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
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Opening Balance as per Company Books: <input id="opening_company_book" name="opening_company_book" type="number" readonly value="0" class="form-control">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Deposits in Transit: <input id="deposits" name="deposits" type="number" readonly value="0" class="form-control">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Outstanding Cheques: <input id="outstanding" name="outstanding" type="number" readonly value="0" class="form-control">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Balance as per Company Books <input id="balance_company_book" name="company_book_balance" type="number" readonly value="0" class="form-control">
        </div>
        
        <br>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Balance as per Bank Statement: <input id="balance_bank_statement" name="bank_statement_balance" type="number" readonly value="0" class="form-control">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Difference: <input id="difference" name="difference" type="number" readonly value="0" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4 padtb text-right">
        <div class="col-md-9"></div>    
        <div class="col-md-3 my-lab" style="    display: flex;">
            <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
        </div>    
</div>
<script>
    $(document).ready(function(e) {
        
        $('#bank_opening_erp').val($('#erp_amount').val());

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


    function checkRow(e) 
    {
        var isChecked = e.target.checked;
        var value = e.target.value;
        if (isChecked) {
            $('#cl_date' + value).removeAttr('readonly');
            $('#check_type' + value).val(1);
        } else {
            $('#cl_date' + value).attr('readonly', 'readonly');
            $('#check_type' + value).val(0);

        }
    }

    function calculationBydate()
    {
        var checkbox = document.querySelectorAll('.checkbox')
        var toDate = $('#toDate').val();
        var total_debit = 0 ;
        var total_credit = 0 ;
        var amount;
        checkbox.forEach((e)=>{
            if(e.checked)
            {
                if($('#cl_date'+e.value).val() > toDate)
                {
                    if($('#type'+e.value).val() == 'dr')
                    {
                        amount = Number($('#debit_amount'+e.value).val());
                        // console.log(amount)
                        // console.log('#debit_amount'+e.value)
                        total_debit += amount;
                        
                    }
                    else if($('#type'+e.value).val() == 'cr')
                    {
                        amount = Number($('#credit_amount'+e.value).val());
                        // console.log('#credit_amount'+e.value)
                        
                        total_credit += amount; //$('#credit_amount'+e.value).val()
                        // console.log(total_credit)
                    }
                    
                }
                
            }
        })

        var erpAmount = + $('#erp_amount').val()
        $('#opening_company_book').val(erpAmount);
        $('#deposits').val(total_debit);
        $('#outstanding').val(total_credit);
        
        let total = erpAmount + total_credit;
        total = total - total_debit ;

        $('#balance_company_book').val(total);

        let bank_opening_custom = + $('#bank_opening_custom').val();
        $('#balance_bank_statement').val(bank_opening_custom);

        let difference = total - bank_opening_custom;

        $('#difference').val(difference);



        
    }


    function calculation()
    {
        let balance_company_book = + $('#balance_company_book').val();
        let bank_opening_custom = + $('#bank_opening_custom').val();
        // let bank_closing_custom = + $('#bank_closing_custom').val();
       
        let difference = balance_company_book - bank_opening_custom;

        $('#balance_bank_statement').val(bank_opening_custom);
       
       
        $('#difference').val(difference);

        // let erp_amount = + $('#erp_amount').val();

        // $('#opening_company_book').val(bank_opening_custom)
    }




</script>

