<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;$total=0;
$paramOne = "sdc/viewQuotationDetail?m=".$m;
$paramTwo = "sdc/viewQuotationDetailTwo?m=".$m;
?>

@foreach($quotation as $row)
    <tr id="<?= $row->id ?>" >
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{ $row->quotation_no }}</td>
        <td class="text-center">{{ $row->tracking_no }}</td>
        <?php  ?>
        <td class="text-center">{{ CommonHelper::get_client_name_by_id($row->client_id)}}</td>
        <td class="text-center">{{ $row->quotation_to }}</td>
        <td class="text-center">{{ $row->designation }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($row->quotation_date) }}</td>
        <td id="{{$row->id}}" class="">
            <?php if($row->quotation_status == 1):?>
            <span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>
            <?php else:?>
            <span class="badge badge-success" style="background-color: #00c851 !important">Success</span>
            <?php endif;?>
        </td>
        <td class="text-center">
            <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','View Quotation Detail')" type="button" class="btn btn-success btn-xs">View</button>
        </td>
        <?php if($row->quotation_status == 1):?>
        <td class="text-center">
            <button onclick="QuotationDelete('<?= $row->id ?>','smfal/QuotationDelete')" class="btn btn-success btn-xs">Delete</button>
        </td>
        <?php else:?>
        <td class="text-center">
            <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
        </td>
        <?php endif;?>

    </tr>

@endforeach