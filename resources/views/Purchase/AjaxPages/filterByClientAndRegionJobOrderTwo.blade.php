<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m  = $_GET['m'];
$counter = 1;$total=0;
$edit_url= url('/purchase/editJobOrder/');
$paramOne = "pdc/viewJobOrderDetail?m=".$m;
$EstimatView = "pdc/viewEstimateDetail?m=".$m;

CommonHelper::companyDatabaseConnection($m);
$ClientJob = DB::table('client_job')->where('status','=',1)->get();
CommonHelper::reconnectMasterDatabase();

?>

@foreach($joborder as $row)
    <?php $client_name = CommonHelper::client_name($row->client_name);
    $count= CommonHelper::check_job_order_data_count($row->job_order_id);
    ?>
    <tr id="<?= $row->job_order_id ?>" @if($count==0) style="background-color: lightcoral" @endif  @if($row->date==date('Y-m-d')) style="background-color: lightyellow" @endif title="{{$row->job_order_id}}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{ $row->job_order_no }}</td>
        <td title="{{$row->job_order_id}}" class="text-center"><?php  echo CommonHelper::changeDateFormat($row->date_ordered);?></td>
        <td class="text-center"><?php echo $client_name->client_name?></td>
        <td class="text-center">
            <select name="BranchId<?php echo $row->job_order_id?>" id="BranchId<?php echo $row->job_order_id?>">
                <option value="">Select Branch</option>
                <?php foreach($Branch as $Fil):?>
                <option value="<?php echo $Fil->id?>"><?php echo $Fil->branch_name?></option>
                <?php endforeach;?>
            </select>
            <span id="Error<?php echo $row->job_order_id?>"></span>
        </td>
        <td class="text-center" id="ShowHide<?php echo $row->job_order_id?>">
            <?php if($row->branch_id ==0):?>
            <button type="button" class="btn btn-sm btn-primary" id="BtnUpdate<?php echo $row->job_order_id?>" onclick="UpdateBranchId('<?php echo $row->job_order_id?>')">Update</button></td>
        <?php endif;?>
        <script !src="">
            $('#BranchId'+'<?php echo $row->job_order_id?>').select2();
        </script>
        <td class="text-center">
            <?php $ClientJob = CommonHelper::get_single_row('client_job','id',$row->client_job);
            if($ClientJob)
            {
                echo $ClientJob->client_job;
            }
            ?>
        </td>

        <td class="text-center">{{ $row->contact_person }}</td>

        <td class="text-center">{{ $row->job_location }}</td>
        <td class="text-center">{{ $row->client_address }}</td>







    </tr>
    {{--<tr>--}}
    {{--<td class="text-center" colspan="4">--}}
    {{--<select name="ClientJobId" id="ClientJobId<?php echo $row->job_order_id?>" class="form-control select2">--}}
    {{--<option value="">Select</option>--}}
    {{--< ?php foreach($ClientJob as $Fil):?>--}}
    {{--<option value="< ?php echo $Fil->id?>">< ?php echo $Fil->client_job?></option>--}}
    {{--< ?php endforeach;?>--}}
    {{--</select>--}}
    {{--</td>--}}
    {{--<td class="text-center">--}}
    {{--<button type="button" class="btn btn-sm btn-success" onclick="UpdateDpdn('< ?php echo $row->job_order_id?>')">Update</button>--}}
    {{--</td>--}}
    {{--</tr>--}}

@endforeach
<script !src="">


    $(document).ready(function(){
        $('.select2').select2();
    });
</script>