<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$supplier_id = $_GET['supplier_id'];

?>
<div class="row" id="data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo Form::open(array('url' => '/PaymentPurchaseVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
        <div class="panel">
            <div class="panel-body">
                <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <h5 style="text-align: center" id="h3"></h5>
                            <table class="<?php echo $_GET['class']?> table-bordered sf-table-list" id="bankPaymentVoucherList">
                                <thead>
                                <th class="text-center"></th>
                                <th class="text-center">S.No</th>
                                <th class="text-center">P.V. No.</th>
                                <th class="text-center">P.V. Date</th>
                                <th class="text-center">Bill Date.</th>
                                <th class="text-center">Slip No</th>
                                <th class="text-center">PO No</th>
                                <th class="text-center">Purchased Amount</th>
                                <th class="text-center">Return Amount</th>
                                <th class="text-center">Paid Amount</th>
                                <th class="text-center">Remaining</th>

                                </thead>
                                <tbody id="filterBankPaymentVoucherList">
                                <?php
                                $NewPurchaseVoucher = CommonHelper::NewPurchaseVoucherBySupplierId($supplier_id);
                                $counter = 1;
                                $RemainingAmount = 0;

                                $total_remaining_amount=0;
                                $total_paid_amount=0;
                                foreach ($NewPurchaseVoucher as $row1) {
                                $PurchaseAmount = CommonHelper::PurchaseAmountCheck($row1->id);
                                $PurchaseTaxAmount = CommonHelper::PurchaseTaxAmountCheck($row1->id);
                                $PurchaseAmount = $PurchaseAmount + $PurchaseTaxAmount;
                                $PaymentAmount = CommonHelper::PaymentPurchaseAmountCheck($row1->id);


                                $return_amount=  DB::Connection('mysql2')->table('purchase_return as a')
                                ->join('purchase_return_data as b','a.id','b.master_id')
                                ->where('a.status',1)
                                ->where('a.type',2)
                                ->where('grn_no',$row1->grn_no)
                                ->sum('b.net_amount');


                                $po_no='';
                                if ($row1->purchase_type==1):
                                $po_no=$row1->description;
                                $po_no=explode('||',$po_no);
                                $po_no=$po_no[1];
                                endif;

                                if($PaymentAmount != "")
                                {
                                $RemainingAmount = $PurchaseAmount-$PaymentAmount-$return_amount;
                                } else
                                {
                                $RemainingAmount = $PurchaseAmount-$return_amount;
                                $PaymentAmount = 0;
                                }

                                ?>
                                <tr id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
                                    <td class="text-center">
                                        <?php if($RemainingAmount>0){ ?>
                                        <input name="checkbox[]" class="checkbox1" id="1chk<?php echo $counter?>" type="checkbox" value="<?php echo $row1->id ?>" onclick="checking()" />
                                        <?php } else {
                                        echo '<span class="label label-default">Clear</span>';
                                        } ?>
                                    </td>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center hidden-print">
                                        <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row1->id;?>','View Bank P.V Detail','<?php echo $_GET['m']?>')" class="btn btn-xs btn-success"><?php echo strtoupper($row1->pv_no);?></a>
                                    </td>
                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date); ?></td>
                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->bill_date); ?></td>
                                    <td class="text-center"><?php echo $row1->slip_no; ?></td>
                                    <td class="text-center"><?php echo $po_no; ?></td>
                                    <td class="text-center"><?php echo number_format($PurchaseAmount,2); ?></td>
                                    <td class="text-center"><?php echo number_format($return_amount,2); ?></td>
                                    <td class="text-center"><?php echo number_format($PaymentAmount,2); ?></td>
                                    <td class="text-center"><?php echo number_format($RemainingAmount,2); ?></td>
                                    <?php

                                    $total_remaining_amount+=$RemainingAmount;
                                    $total_paid_amount+=$PaymentAmount;
                                    ?>
                                    {{--<td class="text-center">< ?php echo CommonHelper::get_supplier_name($row1->supplier); ?></td>--}}
                                    {{--<td class="text-center">< ?php echo $row1->description; ?></td>--}}
                                    {{--<td class="text-center">< ?php if($row1->pv_status == 2){echo "Approved";} else{echo "Pending";}?></td>--}}


                                    <td class="text-center hidden-print">
                                        <a onclick="showDetailModelOneParamerter('adjust_amount/<?php echo $row1->id;?>/{{$row1->supplier}}','','Adjust Amount','<?php echo $_GET['m']?>')" class="btn btn-sm btn-success"> AdJust Amount</a>
                                    </td>

                                </tr>


                                <?php
                                }
                                ?>
                                <tr class="text-center" style="font-size: large;font-weight: bold">
                                    <td colspan="8">Total</td>
                                    <td colspan="1">{{number_format($total_paid_amount,2)}}</td>
                                    <td colspan="1">{{number_format($total_remaining_amount,2)}}</td>
                                    <td></td>
                                </tr>

                                <?php


                                $data=CommonHelper::get_advancee_from_outstanding($supplier_id);

                                foreach($data as $row):


                                $diffrence=CommonHelper::get_debit_credit_from_outstanding($supplier_id,$row->new_pv_no);

                                if ($diffrence<0):?>


                                <tr style="background-color: darkgrey" id="" title="" id="">
                                    <td class="text-center">

                                    </td>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-center"><?php echo $row->new_pv_no ?></td>
                                    <td class="text-center"><?php echo 'Advance' ?></td>
                                    <td class="text-center"><?php echo 'Advance' ?></td>
                                    <td class="text-center"><?php echo 'Advance' ?></td>
                                    <td class="text-center"><?php echo  $diffrence; ?></td>
                                    <td  class="text-center"><b style="font-size: larger;font-weight: bolder"><?php echo $diffrence; ?></b></td>

                                    <td class="text-center"><?php echo 'Advance'?></td>
                                    <td class="text-center"><?php echo 'Advance' ?></td>
                                </tr>
                                <?php endif;endforeach ?>
                                <?php
                                ?>

                                <tr>
                                    <th colspan="10" class="text-center">xxxxx</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-sm btn-primary" id="BtnPayment" >Payment</button>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>

<script>
    $(document).ready(function(){

    });

    function checking()
    {
        $('.checkbox1').each(function()
        {
            if ($(this).is(':checked'))
            {
                $('#BtnPayment').prop('disabled',false);
            }
            else
            {
                $('#BtnPayment').prop('disabled',false);
            }
        });
    }

</script>