<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(279);
$edit=ReuseableCode::check_rights(280);
$delete=ReuseableCode::check_rights(281);


$Counter = 1;
$total_amount=0;
foreach ($Data as $Fil) {
$edit_url= url('/finance/edit_new_pv/'.$Fil->id.'?m='.$m);
?>
<tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
    <td class="text-center"><?php echo $Counter++;?></td>
    <td class="text-center"><?php echo strtoupper($Fil->pv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($Fil->pv_date);?></td>
    <td class="text-center"><?php echo $Fil->supplier_invoice_no?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($Fil->supplier_invoice_date);?></td>
    <td class="text-center"><?php echo CommonHelper::get_supplier_name($Fil->supplier_id);?></td>
    <td class="text-center"><?php

        $gros= DB::Connection('mysql2')->table('new_pvv_data')->where('master_id',$Fil->id)->sum('amount');
        echo number_format($gros,2);
        ?></td>

    <td class="text-center"><?php

        $tax=$Fil->sales_tax_amount;
        echo number_format($tax,2);
        ?></td>

    <td>{{number_format($tax+$gros,2)}}</td>
    <?php $total_amount+=$tax+$gros; ?>

    <td id="status{{$Fil->id}}"  class="text-center"><?php if ($Fil->pv_status==1):  echo  'pending'; else: echo 'Approved'; endif?></td>
    <td class="text-center hidden-print">
        <?php if($view == true):?>
        <a onclick="showDetailModelOneParamerter('finance/view_new_pv_detail','<?php echo $Fil->id;?>','View New PV Detail','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success">View</a>
        <?php endif;?>
        <?php if($Fil->pv_status == 1):?>
        <?php if($edit == true):?>
        <a href="<?php echo $edit_url?>" type="button" class="btn btn-xs btn-primary">Edit</a>
        <?php endif;?>
        <?php endif;?>
        <?php if($delete == true):?>
        <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $Fil->id?>" onclick="DeleteNewPv('<?php echo $Fil->id?>','<?php echo $Fil->pv_no?>')">Delete</button>
        <?php endif;?>

    </td>
</tr>
<?php
}
?>
<tr>
    <th colspan="8" class="text-center">Total</th>
    <th colspan="1" class="text-center">{{number_format($total_amount,2)}}</th>

</tr>