<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\Account;
 $date = $_GET['date'];
$m = $_GET['m'];

$counter = 1;
$index = 0;
$makeTotalAmount = 0;

$finacne = array("Journal Vouchers"=>"jvs", "Payment Vouchers"=>"pvs", "Receipt Vouchers"=>"rvs","Contra Vouchers"=>"contra");
$master_data=['jv_data','pv_data','rv_data','contra_data'];
$voucher_no=['jv_no','pv_no','rv_no','cv_no'];
$voucher_date=['jv_date','pv_date','rv_date','cv_date'];
$view=['viewJournalVoucherDetail','viewBankPaymentVoucherDetail','viewBankReceiptVoucherDetail','viewContraVoucherDetail'];
$heading=['View Journal Voucher Detail','View Payment Voucher Detail','View Receipt Voucher Detail','View Contra Voucher Detail'];
$edit_voucher=['editJournalVoucherForm','View Payment Voucher Detail','editBankReceiptVoucherForm','editContraVoucher'];
        //print_r($edit_voucher); die();
$toal=0;
?>

        @foreach($finacne as $key => $value)
        <h3 style="text-align: center"><u>{{$key}}</u></h3>

        @foreach (CommonHelper::dayBookQuery($value,$voucher_no[$index],$voucher_date[$index],$date) as $row1)

        <tr>
            <td><?php echo $index;?></td>
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->voucher_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->voucher_date);?></td>
            <td class="text-center"><?php  echo   CommonHelper::debit_credit_amount($master_data[$index],$row1->id);?></td>
            <td class="text-center"><?php echo $row1->slip_no;?></td>
            <td class="text-center"><?php // if($row1->jv_status == 2){echo "Approved";} else{echo "Pending";}?></td>
            <td class="text-right"><?php $amount=CommonHelper::total_amount_for_book_day($master_data[$index],$row1->id);
                echo number_format($amount,2);
                   $toal+= $amount;
                ?></td>

            <?php
            $jv_proccess=0;
            if ($index==0):
            $jv_proccess= CommonHelper::check_status($value,$row1->id); endif;?>


            <td class="text-center hidden-print"> <a onclick="showDetailModelOneParamerter('fdc/<?php echo $view[$index] ?>','<?php echo $row1->id;?>','<?php echo $heading[$index]  ?>','<?php echo $m?>')" class="btn btn-xs btn-success">View</a>
            <?php if($index==3):?>
                    <a href="<?php echo  URL::to('/finance/'.$edit_voucher[$index].'/'.$row1->id.'?m='.$m); ?>"  type="button" class="btn btn-primary btn-xs">Edit</a>
            <?php endif;?>
             @if ($index==0 || $index==2)
              @if($jv_proccess==0)
               <a   href="<?php echo  URL::to('/finance/'.$edit_voucher[$index].'/'.$row1->id.'?m='.$m); ?>"  type="button" class="btn btn-primary btn-xs">Edit</a>
              @else <button onclick="alert('You cannot Edit this Voucher Because You Have Made the Payment Against This Voucher')" class="btn btn-primary btn-xs">Edit</button>    @endif @endif
            </td>
        </tr>


        @endforeach
        <tr style="background-color: darkgray">
            <td colspan="6">Total</td>
            <td colspan="1">{{number_format($toal,2)}}</td>
        </tr>
        <?php
        $toal=0;
        $index++;
        $counter = 1;
        ?>
        @endforeach





