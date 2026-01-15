<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(160);
$edit=ReuseableCode::check_rights(161);
$delete=ReuseableCode::check_rights(162);

$counter = 1;
$makeTotalAmount = 0;
foreach ($pvs as $row1) {
    ?>
<tr @if ($row1->type==2) style="background-color: darkgray" @endif
class="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>

    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>
    <td class="text-center">
        <?php echo $row1->description ;?>
    </td>
    <td class="text-center"><?php echo $row1->bill_no;?></td>
    <td class="text-center"><?php echo $row1->cheque_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
    <td class="text-center">{{ $row1->verified_by != null ? 'Yes' : 'No' }}</td>
    <td class="text-center status{{$row1->pv_no}}"><?php if($row1->pv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
        <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
    <td class="text-center hidden-print">


             <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>

                            <?php if($view == true):?>
                        <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetail','<?php echo $row1->id;?>','View Bank P.V Detail','<?php echo $_GET['m']?>','')" class="">
                            View
                        </a>
                        <?php endif;?>

                            <?php if($edit == true):?>
                        <a  href="<?php echo  URL::to('/finance/editBankPaymentNew/'.$row1->id.'?m='.$m); ?>" type="button" class=" BtnHide<?php echo $row1->pv_no?>">
                            Edit
                        </a>
                        <?php endif;?>
                            <?php if($delete == true):?>
                        <a class=" BtnHide<?php echo $row1->pv_no?>" type="button" onclick="DeletePvActivity('<?php echo $row1->id;?>','<?php echo $row1->pv_no?>','<?php echo $row1->pv_date?>','<?php echo CommonHelper::GetAmount('new_pv_data',$row1->id)?>')">Delete</a>
                        <?php endif;?>
                        <a target="_blank" href="<?php echo url('fdc/viewBankPaymentVoucherDetailPrint?id='.$row1->id.'&&m='.$m)?>" class="">Print</a>
                            <?php
                            /*
                        if($row1->pv_status == 1):
                        date_default_timezone_set('Asia/karachi');
                        $PvId = $row1->id;
                        $PvNo = $row1->pv_no;
                        $UserName = Auth::user()->username;
                        $DeleteDate = date('Y-m-d');
                        $DeleteTime = date('h:i:s');
                        $ActivityType = 2;
                            */
                            ?>
                        {{--<button class=""--}}
                        {{--onclick="DeletePvActivity('< ?php echo $PvId;?>','< ?php echo $PvNo;?>','< ?php echo $UserName;?>','< ?php echo $DeleteDate;?>','< ?php echo $DeleteTime;?>','< ?php echo $ActivityType;?>')">--}}
                        {{--Delete</button>--}}
                            <?php //endif?>

                    </li>
                </ul>
            </div>
    </td>
</tr>
    <?php
}
?>
<tr>
    <th colspan="10" class="text-center">xxxxx</th>
</tr>
