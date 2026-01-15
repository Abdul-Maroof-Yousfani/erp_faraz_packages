<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;


$view=ReuseableCode::check_rights(214);
$edit=ReuseableCode::check_rights(215);
$delete=ReuseableCode::check_rights(216);

$counter = 1;
$makeTotalAmount = 0;

foreach ($pvs as $row1) {
?>
<tr @if ($row1->type==2) style="background-color: darkgray" @endif class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>

    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
    <td class="text-center"><?php if ($row1->payment_type==1): echo strtoupper($row1->cheque_no); else: echo  'Cash'; endif?></td>
    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>


    <td class="text-center status{{$row1->pv_no}}"><?php if($row1->pv_status == 2)
        {
            echo "<span style='color: green'>Approved</span>";
        }
        else
        {
            echo "<span style='color: red'>Pending</span>";
        }
        ?></td>


    <td class="text-center hidden-print">
        <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetailInDetailImport','<?php echo $row1->id;?>','View Cash P.V Detail','<?php echo $_GET['m']?>')" class="btn btn-xs btn-success"> View</a>
    </td>
</tr>
<?php
}
?>