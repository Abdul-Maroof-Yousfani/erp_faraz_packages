<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$counter = 1;
$makeTotalAmount = 0;


foreach ($NewPurchaseVoucher as $row1) {
?>
<tr id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
    {{--<td class="text-center">--}}
    {{--< ?php if($row1->pv_status ==1):?>--}}
    {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
    {{--< ?php endif;?>--}}
    {{--</td>--}}
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
    <td class="text-center"><?php echo strtoupper($row1->grn_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->bill_date);?></td>
    {{--<td class="text-center">< ?php echo $Account = CommonHelper::debit_credit_amount('new_purchase_voucher_data',$row1->id);?></td>--}}
    <td class="text-center"><?php echo $row1->slip_no;?></td>
    <td class="text-center">
        <?php $Vendor = CommonHelper::get_single_row('supplier','id',$row1->supplier);
        echo $Vendor->name;
        ?>
    </td>
    <?php //die();?>

    <td id="Append{{$row1->id}}" class="text-center">
        <?php if($row1->pv_status == 1):?>
        <span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>
        <?php else:?>
        <span class="badge badge-success" style="background-color: #00c851 !important">Success</span>
        <?php endif;?>
    </td>
    <td><?php echo CommonHelper::get_purchase_amount($row1->id)?></td>
    <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
    <td class="text-center hidden-print">
        {{--<a href="< ?php echo  URL::to('/finance/editCashPVForm/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs">Edit</a>--}}
        <input class="btn btn-xs btn-danger" type="button" onclick="DeletePvActivity('<?php echo $row1->id;?>')" value="Delete" />
        <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row1->id;?>','View Purchase Voucher Detail','<?php echo $m?>')" class="btn btn-xs btn-success">View</a>
        <?php if ($row1->pv_status==1):?>
        <a href="<?php echo  URL::to('/finance/editPurchaseVoucherFormNew/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs">Edit</a>
        <?php endif;?>
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
    </td>
</tr>
<?php
}
?>
<tr>
    <th colspan="8" class="text-center">xxxxx</th>
</tr>