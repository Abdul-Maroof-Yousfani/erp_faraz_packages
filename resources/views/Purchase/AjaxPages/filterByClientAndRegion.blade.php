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
    $Branch = CommonHelper::get_single_row('branch','id',$row->branch_id);
    $count= CommonHelper::check_job_order_data_count($row->job_order_id);
    ?>
    <tr id="<?= $row->job_order_id ?>" @if($count==0) style="background-color: lightcoral" @endif  @if($row->date==date('Y-m-d')) style="background-color: lightyellow" @endif title="{{$row->job_order_id}}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{ $row->job_order_no }}</td>
        <td title="{{$row->job_order_id}}" class="text-center"><?php  echo CommonHelper::changeDateFormat($row->date_ordered);?></td>
        <td class="text-center"><?php echo $client_name->client_name?></td>
        <td class="text-center"><?php echo $Branch->branch_name?></td>
        <td class="text-center">
            <?php $ClientJob = CommonHelper::get_single_row('client_job','id',$row->client_job);
            if($ClientJob)
            {
                echo $ClientJob->client_job;
            }
            ?>
        </td>

        <td class="text-center">{{ $row->contact_person }}</td>
        <td class="text-center">{{ $row->client_address }}</td>

        
        <td class="text-center">
            <?php if($row->type == "")
            {echo '<span class="badge badge-success" style="background-color: #5bc0de !important">Direct</span>';}
            elseif($row->type == 1){echo '<span class="badge badge-success" style="background-color: #5bc0de !important">Through Quotation</span>';}
            else{'<span class="badge badge-success" style="background-color: #5bc0de !important">Through Survey</span>';}?>
        </td>
        <td class="text-center" id="m<?= $row->job_order_id ?>"><?php if($row->jvc_to_managaer==1){ echo '<span class="badge badge-success" style="background-color: #5bc0de !important">JVC SUBMITTED</span>'; }
            else{ ?><a ondblclick="job_Order_Jvc_Submitted('<?= $row->job_order_id ?>','smfal/job_Order_Jvc_Submitted')"><span class="badge badge-warning" style="background-color: #fb3 !important;">JVC NOT SUBMITTED</span></a><?php } ?>
        </td>
        <?php $date1=date_create(date("Y-m-d"));
        $date2=date_create($row->date);
        $diff=date_diff($date2,$date1);
        $days = $diff->format("%r%a");?>
        <td class="text-center"style="color: <?php if($days > 0){echo "red";}?>">
            <?php echo $days;?>
        </td>
        <td id="{{$row->job_order_id}}" class="">
            <?php if($row->jo_status == 1):?>
            <span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>
            <?php else:?>
            <span class="badge badge-success" style="background-color: #00c851 !important">Success</span>
            <?php endif;?>
        </td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"> ACTION <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-default pull-right sf-dropdown-ul" role="menu">
                    <li>
                        <a href="#" onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->job_order_id ?>','Job Order')"><i class="entypo-layout"></i> View</a>
                    </li>
                    <li>
                        <a href='<?php echo  $edit_url.'/'.$row->job_order_id.'/1'.'?m='.$m ?>'>Duplicate</a>
                    </li>

                    <?php if($row->invoice_created == 0):?>
                    <li>
                        <a href='<?php echo  $edit_url.'/'.$row->job_order_id.'?m='.$m ?>'  >Edit</a>
                    </li>

                    <li>
                        <a onclick="jobOrderDelete('<?= $row->job_order_id ?>','smfal/jobOrderDelete')">Delete</a>
                    </li>
                    <?php endif;?>
                    <?php if($row->jo_status == 2 ):?>
                    <li>

                        <?php if($row->invoice_created == 0):
                        ?>
                        <a id="BtnInvoice<?php echo $row->job_order_id?>" href="<?php echo URL('sales/createInvoiceForm/'.$row->job_order_id.'?&&m='.$m)?>">Create Invoice</a>
                        <?php endif;?>
                    </li>
                    <li>
                        <a href='<?php echo URL('purchase/job_order_next_step_edit?master_id='.$row->job_order_id.'&&region_id='.$row->region_id.'&&m='.$m.''); ?>'>Estimate</a>
                    </li>
                    <?php endif;?>
                    <?php if(CommonHelper::EstimateCount($row->job_order_id)>0):?>
                    <li>
                        <a href="#" onclick="showDetailModelOneParamerter('<?= $EstimatView ?>','<?= $row->job_order_id ?>','Job Order')"><i class="entypo-layout"></i> View Estimate</a>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
        </td>




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