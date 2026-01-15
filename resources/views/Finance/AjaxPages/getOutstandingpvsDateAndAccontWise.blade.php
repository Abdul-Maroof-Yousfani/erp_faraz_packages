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
    <tr @if ($row1->type==2)  @endif class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
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
            ?>
        </td>
        <td class="text-center hidden-print">

            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                            <?php if($view == true):?>
                                <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetailInDetail','<?php echo $row1->id;?>','View Cash P.V Detail','<?php echo $_GET['m']?>')" class="dropdown-item_sale_order_list dropdown-item"><i class="fa-regular fa-eye"></i> View</a>
                                <?php endif;?>
                            @if ($row1->pv_status==1)
                                    <?php if($edit == true):?>
                                    <a href="<?php echo url('/editPaymentPurchaseVoucher/'.$row1->id.'?m='.$m); ?>" type="button" class="dropdown-item_sale_order_list dropdown-item BtnHide<?php echo $row1->pv_no?>"><i class="fa-solid fa-pencil"></i> Edit</a>
                                    <?php endif;?>
                            
                                @endif
                                    <?php if($delete == true):?>
                                        <a class="dropdown-item_sale_order_list dropdown-item BtnHide<?php echo $row1->pv_no?>" type="button" onclick="DeletePvActivity('<?php echo $row1->id;?>','<?php echo $row1->pv_no?>','<?php echo $row1->pv_date?>','<?php echo CommonHelper::GetAmount('new_pv_data',$row1->id)?>')"><i class="fa-solid fa-trash"></i> Delete </a>
                                        <?php endif;?>
                                <?php if($row1->payment_type == 1 && $row1->pv_status == 2):?>
                                        <a class="dropdown-item_sale_order_list dropdown-item BtnReturn<?php echo $row1->pv_no?>"  type="button" onclick="VoucherReturn('<?php echo $row1->id;?>','<?php echo $row1->pv_no?>','<?php echo $row1->pv_date?>','<?php echo CommonHelper::GetAmount('new_pv_data',$row1->id)?>','<?php echo $row1->cheque_no?>')"><i class="fa-solid fa-rotate-left"></i> Return </a>
                                <?php endif;?>
                                    <a target="_blank" href="<?php echo url('fdc/viewBankPaymentVoucherDetailInDetailPrint?id='.$row1->id.'&&m='.$m)?>" class="dropdown-item_sale_order_list dropdown-item"><i class="fa-solid fa-print"></i> Print</a>
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
                            {{--<button class="dropdown-item_sale_order_list dropdown-item"--}}
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