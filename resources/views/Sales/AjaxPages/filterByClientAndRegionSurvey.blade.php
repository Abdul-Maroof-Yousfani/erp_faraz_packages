<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;$total=0;
$edit_url= url('/purchase/editSurvey/');
$paramOne = "sdc/viewSurveyListDetail?m=".$m;
?>

@foreach($survey as $row)
    <tr id="<?php echo $row->survey_id;  ?>" >
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{$row->tracking_no}}</td>
        <td class="text-center">{{ CommonHelper::get_client_name_by_id($row->client_id) }}</td>

        <td class="text-center">{{ $row->branch_name }}</td>
        <td class="text-center">{{ $row->contact_person }}</td>
        <td class="text-center">{{ $row->contact_number }}</td>
        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->survey_date); ?></td>
        <td class="text-center">{{ CommonHelper::gey_survey_by_name($row->survery_by_id) }}</td>
        <td class="text-center">{{ $row->surveyor_name }}</td>
        <td class="text-center"><?php $region=CommonHelper::get_rgion_name_by_id($row->region_id); echo $region['region_name']; ?></td>
        <td class="text-center"><?php $city=CommonHelper::get_all_cities_by_id($row->city_id); echo $city['name']; ?></td>
        <td id="{{$row->survey_id}}" class="">
            <?php if($row->survey_status == 1):?>
            <span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>
            <?php else:?>
            <span class="badge badge-success" style="background-color: #00c851 !important">Success</span>
            <?php endif;?>
        </td>
        <td class="text-center"><button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->survey_id ?>','Survey List Detail')" type="button" class="btn btn-success btn-xs">View</button></td>
        <?php if($row->survey_status == 1):?>
        <td>
            <a href='<?php echo  $edit_url.'/'.$row->survey_id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs">Edit</a>

        </td>
        <td>
            <button onclick="SurveyDelete('<?= $row->survey_id ?>','smfal/deleteSurvey')" class="btn btn-danger btn-xs">Delete</button>
        </td>
        <?php else:?>
        <td class="text-center">
            <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
        </td>
        <td class="text-center">
            <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
        </td>
        <?php endif;?>
    </tr>

@endforeach