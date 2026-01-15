


<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$from=Input::get('fromDate');
$to=Input::get('toDate');
$acc_id=explode(',',Input::get('accountName'));
$acc_id  =$acc_id[0];

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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                echo ' '.'('.date('D', strtotime($x)).')';?></label>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 style="text-align: center;">Ledger Report</h3>
        </div>
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
            <td colspan="8" style="font-size: 20px;" class="text-center"><b>Company Name:   (<?php echo FinanceHelper::getCompanyName(Session::get('run_company'));?>)</b></td>
        </tr>
        <tr>
            <td colspan="8" style="font-size: 20px;" class="text-center"><b>Account Name:   (<?php echo CommonHelper::get_account_code($acc_id).'---'.CommonHelper::get_account_name($acc_id);?>)</b></td>
        </tr>
        <tr>
            <td style="font-size: 20px;" class="text-center" colspan="8"><b>From Date: (<?php echo date_format(date_create($from), 'd-m-Y');?>)==========To Date: (<?php echo date_format(date_create($to), 'd-m-Y');?>)</b></td>

        </tr>


        <tr>
            <th style="width: 100px" class="text-center">Voucher No</th>
            <th style="width: 120px" class="text-center">Date</th>
            <th style="width: 120px" class="text-center">V Type</th>
            <th style="width: 120px" class="text-center">Cheque No</th>
            <th style="width: 120px" class="text-center">Description</th>
            <th class="text-center" style="width:100px;">Dr</th>
            <th class="text-center" style="width:100px;">Cr.</th>
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
            <td></td>
            <td class="text-left" colspan="3">Opening Balance</td>
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
            <td class="text-center">{{$type}}</td>
            <td class="text-left"><?php echo $cheque_no.'</br>';if ($cheque_date!='0000-00-00' && $cheque_date!=''): date_format(date_create($cheque_date), 'd-m-Y');endif; ?></td>
            <td class="text-left">
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
            <td class="text-center" colspan="5"><b style="font-size: large;">TOTAL</b></td>
            <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_debit,2) ?></b></td>
            <td class="text-right" colspan="1"><b style="font-size: large;"><?php echo  number_format($total_credit,2) ?></b></td>
            <td  class="text-center" colspan="1"><b style="font-size: large;color: #ff9999"><?php // echo  number_format($total_debit-$total_credit) ?></b></td>

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
