<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(153);
$edit=ReuseableCode::check_rights(154);
$delete=ReuseableCode::check_rights(155);

$counter = 1;
$makeTotalAmount = 0;
        ?>
<?php
foreach ($NewJvs as $row1) {
?>

<tr class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->jv_status == 1):?>
onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
    
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->jv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->jv_date);?></td>
    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_jv_data',$row1->id);?></td>
    {{--<td class="text-center">< ?php echo $row1->slip_no;?></td>--}}
    <?php //die();?>
    <td class="text-center status{{$row1->jv_no}}"><?php if($row1->jv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
    <?php   $count=CommonHelper::check_amount_in_ledger($row1->jv_no,$row1->id,2) ?>
    <td></td>
    <td class="text-center hidden-print">
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>

                        {{--<a href="< ?php echo  URL::to('/finance/editCashPVForm/'.$row1->id.'?m='.$m); ?>" type="button" class="">Edit</a>--}}
                        {{--<input class="" type="button" onclick="DeletePvActivity('< ?php echo $row1->id;?>')" value="Delete" />--}}

                        <?php if($view == true):?>
                        <a onclick="showDetailModelOneParamerter('fdc/viewJournalVoucherDetail','<?php echo $row1->id;?>','View Journal Voucher Detail','<?php echo $m?>','')" class="">View</a>
                        <?php endif;?>
                        <?php if($edit == true):?>
                        <a href="<?php echo  URL::to('/finance/editJv/'.$row1->id.'?m='.$m); ?>" type="button" class=" BtnHide<?php echo $row1->jv_no?>">Edit</a>
                        <?php endif;?>
                        <?php if($delete):?>
                        <a class=" BtnHide<?php echo $row1->jv_no?>" type="button"
                            onclick="DeleteJvActivity('<?php echo $row1->id;?>','<?php echo $row1->jv_no?>','<?php echo $row1->jv_date?>','<?php echo CommonHelper::GetAmount('new_jv_data',$row1->id)?>')">Delete</a>
                        <?php endif;?>
                        <a target="_blank" href="<?php echo url('fdc/viewJournalVoucherDetailPrint?id='.$row1->id.'&&m='.$m)?>" class="">Print</a>

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
                        {{--<button class="btn btn-xs btn-danger"--}}
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
    <th colspan="8" class="text-center">xxxxx</th>
</tr>