<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;$total=0;
$paramOne = "sdc/viewComplaintDetail?m=".$m;
?>

@foreach($complaint as $row)
    <?php $client_name = CommonHelper::client_name($row->client_name); ?>
    <tr id="{{ $row->id }}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center"><?php echo $client_name->client_name?></td>
        <td class="text-center"><?php echo $row->branch_name?></td>
        <td class="text-center"><?php echo $row->branch_code?></td>
        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->date); ?></td>
        <td class="text-center"><?php echo $row->contanct_person?></td>
        <td class="text-center"><?php echo $row->designation?></td>
        <td class="text-center">
            <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','View Complaint Detail')" type="button" class="btn btn-success btn-sm">View</button>
        </td>
    </tr>

@endforeach