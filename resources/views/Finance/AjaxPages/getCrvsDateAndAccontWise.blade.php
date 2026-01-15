<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(181);
$edit=ReuseableCode::check_rights(182);
$delete=ReuseableCode::check_rights(183);

$counter = 1;
$makeTotalAmount = 0;
        
foreach ($NewRvs as $row1) {
?>
<tr>
    {{--<td class="text-center">--}}
    {{--< ?php if($row1->pv_status ==1):?>--}}
    {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
    {{--< ?php endif;?>--}}
    {{--</td>--}}
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
    <td class="text-center"><?php echo $row1->ref_bill_no;?></td>
    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>
    {{--<td class="text-center">< ?php echo $row1->slip_no;?></td>--}}
    <?php //die();?>

    <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
        <?php if($row1->rv_status == 1):?>
        <span class="text-danger">Pending</span>
        <?php else:?>
        <span class="text-success">Approved</span>
        <?php endif;?>
    </td>
    <?php   //$count=CommonHelper::check_amount_in_ledger($row1->rv_no,$row1->id,2) ?>
    <td></td>
    <td class="text-center hidden-print">
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>

                        <?php if($view == true):?>
                        <a onclick="showDetailModelOneParamerter('fdc/viewCashRvDetailNew','<?php echo $row1->id;?>','View Cash Reciept Voucher Detail','<?php echo $m?>','')" class="">View</a>
                        <?php endif;?>
                        <?php if($row1->rv_status ==1):?>
                        <?php if($edit == true):?>
                        <a href="<?php echo  URL::to('/finance/editCashRv/'.$row1->id.'?m='.$m); ?>" type="button" class="BtnHide<?php echo $row1->rv_no?>">Edit</a>

                        <?php endif;?>
                        <?php endif;?>

                        @if($delete==true)
                            <a class=" BtnHide<?php echo $row1->rv_no?>" type="button"
                                onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')">Delete</a>

                            @endif
                            <a target="_blank" href="<?php echo url('fdc/viewCashRvDetailNewPrint?id='.$row1->id.'&&m='.$m)?>" class="">Print</a>
                        {{--<a href="< ?php echo  URL::to('/finance/editCashPVForm/'.$row1->id.'?m='.$m); ?>" type="button" class="">Edit</a>--}}
                        {{--<input class="" type="button" onclick="DeletePvActivity('< ?php echo $row1->id;?>')" value="Delete" />--}}

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