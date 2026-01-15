<?php
use App\Helpers\CommonHelper;
$VoucherId = $_GET['id'];
        $VoucherData = DB::Connection('mysql2')->table('new_purchase_voucher_payment')->where('status',1)->where('new_purchase_voucher_id',$VoucherId)->get();
?>
<div class="row" id="printDemandVoucherVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Pv No</th>
                                <th class="text-center">Supplier Name</th>
                                <th class="text-center" >Amount</th>
                                <th class="text-center" >Voucher No</th>
                                <th class="text-center" >Username</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $count = 1;
                            $AmountTotal = 0;
                            foreach ($VoucherData as $val){
                            ?>
                            <tr class="text-center">
                                <td class="text-center">{{$count++}}</td>
                                <td class="text-center">{{$val->pv_no}}</td>
                                <td class="text-center"><?php echo $Acc = CommonHelper::get_supplier_name($val->supplier_id); ?></td>
                                <td class="text-center">{{number_format($val->amount,2)}}<?php $AmountTotal+=$val->amount;?></td>
                                <td  class="text-center"><a target="_blank" href="{{ url('fdc/viewBankPaymentVoucherDetailInDetailDirect?id='.$val->new_pv.'&&m='.Session::get('run_company')).'&&other=1' }}">{{$val->new_pv_no}}</a></td>
                                <td class="text-center">{{$val->username}}</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr class="text-center">
                                <td colspan="3"><strong style="font-size: 18px;">TOTAL</strong></td>
                                <td><strong style="font-size: 18px;"><?php echo number_format($AmountTotal,2);?></strong></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function  get_payment_voucher(pv_no)
    {
        alert(pv_no);
        showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetail','other', pv_no ,'View Payement Voucher Detail','1','');
    }
</script>
