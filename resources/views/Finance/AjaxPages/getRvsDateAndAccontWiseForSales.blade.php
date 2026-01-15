<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(134);
$edit=ReuseableCode::check_rights(135);
$delete=ReuseableCode::check_rights(136);
$approved=ReuseableCode::check_rights(137);
$counter = 1;
$makeTotalAmount = 0;
?>
@foreach ($NewRvs as $row1)
<?php 
$received_data=   SalesHelper::get_received_data($row1->id);
$operational = DB::Connection('mysql2')->selectOne('select accounts.operational as  name from new_rv_data as data
		inner join `accounts` on accounts.id = data.acc_id where  data.master_id = \''.$row1->id.'\'')->name;
?>
<tr @if($operational==0) style="background-color: lightcoral" @endif class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>">
    {{--<td class="text-center">--}}
    {{--< ?php if($row1->pv_status ==1):?>--}}
    {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
    {{--< ?php endif;?>--}}
    {{--</td>--}}
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>

    <td class="text-center"><?php echo $row1->cheque_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date).'</br>'.$operational;?></td>
    <td class="text-center">{{number_format($received_data->net_amount,2)}}</td>
    <td class="text-center">{{number_format($received_data->tax_amount,2)}}</td>
    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>
    {{--<td class="text-center">< ?php echo $row1->slip_no;?></td>--}}
    <?php //die();?>

    <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
       <?php if($row1->rv_status == 1):?>
        <span class="" style="color: #fb3 !important;">Pending</span>
        <?php else:?>
        <span class="" style="color: #00c851 !important">Approved</span>
        <?php endif;?>
    </td>
    <?php   //$count=CommonHelper::check_amount_in_ledger($row1->rv_no,$row1->id,2) ?>
    <td class="text-center hidden-print">

        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>

                    @if($view == true)
                        <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','<?php echo $row1->id;?>','View Bank Reciept Voucher Detail','<?php echo $m?>','')" class=""><i class="fa-regular fa-eye"></i> View</a>
                    @endif

                    @if($row1->rv_status==1)
                    @endif

                    @if($delete == true)
                        <a class="BtnHide<?php echo $row1->rv_no?>" type="button"
                        onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')"
                        ><i class="fa-solid fa-trash"></i> Delete</a>
                    @endif
                    <a onclick="change_colour('{{$row1->id}}')" target="_blank" href="<?php echo url('sdc/viewReceiptVoucherPrint?id='.$row1->id.'&&m='.$m)?>" class=""><i class="fa-solid fa-print"></i> Print</a>

                </li>
            </ul>
        </div>
    </td>
</tr>
@endforeach