<?php
$m = $_GET['m'];
use App\Helpers\CommonHelper;
$Counter = 1;
$paramurl = "pdc/viewJobOrderDetail?m=".$m;
$paramOne = "pdc/viewIssuanceDetail?m=".$m;
$paramThree = "View Issuance Detail";
$paramFour= url('/purchase/editGoodIssuance/');

foreach($Issuance as $dataFil):
$job_order_id = CommonHelper::JobOrderNoData($dataFil->joborder);
// echo $job_order_id
?>
<tr class="text-center" id="RemoveTr<?php echo $dataFil->id?>">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($dataFil->iss_no)?></td>
    <td><?php echo CommonHelper::changeDateFormat($dataFil->iss_date)?></td>
    <td><?php echo $dataFil->description?></td>
    <?php if($dataFil->issuance_type == 1):?>
    <td> <a href="#" onclick="showDetailModelOneParamerter('<?= $paramurl ?>','<?= $job_order_id ?>','Job Order')"><i class="entypo-layout"></i> Job Order </a> </td>
    <?php else:?>
    <td>--</td>
    <?php endif;?>
    <td><?php if($dataFil->issuance_type == 1){echo "Issue With Job Order";}elseif($dataFil->issuance_type == 2){echo "Delivery Challan";}elseif($dataFil->issuance_type == 3){echo "Issue Without Job Order";}elseif($dataFil->issuance_type == 4){echo "Issue Damage Stock";}elseif($dataFil->issuance_type == 5){echo "Damage Delivery Challan Issuance";}else{echo '';}  ?></td>
    <td><?php if($dataFil->issuance_status == 2){echo "Approved";}else{echo "Pending";}?></td>
    <td>

        <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $dataFil->iss_no;?>','<?php echo $paramThree?>')"   type="button" class="btn btn-success btn-xs">View</button>

        <?php if($dataFil->issuance_status == 1):?>
            <a id="BtnEdit<?php echo $dataFil->id?>" href='<?php echo  $paramFour.'/'.$dataFil->id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs">Edit</a>
            <button type="button" class="btn btn-xs btn-success" onclick="ApprovedGoodIssuance('<?php echo $dataFil->id?>')" id="BtnApprove<?php echo $dataFil->id?>"> Approve</button>
        <button type="button" class="btn btn-xs btn-danger" onclick="DeleteIssuance('<?php echo $dataFil->id?>')" id="BtnDelete<?php echo $dataFil->id?>">Delete</button>
        <?php else: ?>
        @if($dataFil->issuance_type == 2 && $dataFil->transfer_status != 1)
            <button type="button" class="btn btn-xs btn-success" onclick="Recieved('<?php echo $dataFil->id?>','<?= $m ?>')" id="recieved<?php echo $dataFil->id?>"> Recieved</button>
        @endif
        <?php endif;?>
        <?php if(Auth::user()->id == 152 || Auth::user()->id == 104 && $dataFil->issuance_status == 2):?>
        <button type="button" class="btn btn-xs btn-danger" onclick="DeleteIssuance('<?php echo $dataFil->id?>')" id="BtnDelete<?php echo $dataFil->id?>">Delete</button>
        <?php endif;?>
    </td>

</tr>
<?php endforeach;?>