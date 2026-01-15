
<?php
$count = 1;
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(381);
$edit=ReuseableCode::check_rights(382);
$delete=ReuseableCode::check_rights(383);
$issuence=ReuseableCode::check_rights(384);
$return=ReuseableCode::check_rights(385);
$conversion_rights=ReuseableCode::check_rights(386);




foreach($data as $row):
$status=  ProductionHelper::check_product_id('production_plane_issuence',$row->id,'main_id');
$conversion=       ProductionHelper::check_conversion($row->id);
$cost=       ProductionHelper::check_cost($row->id);
$issuence_data=       ProductionHelper::check_production_plan_issuence($row->id);
$ppc_completion_data=ProductionHelper::get_completion_date($row->order_no);


$ppc_status=0;


if(!empty($ppc_completion_data->v_date)):

    $ppc_status='Approved';
else:
    if($status==0):
        $ppc_status='Planned';
    else:
        if($conversion>0):
            if($cost>0):
                $ppc_status='Verified';
            else:
                $ppc_status='Complete';
            endif;
        else:
            $ppc_status='Release';
        endif;
    endif;

endif;

if ($request_status=='0'):
    $ppc_status=0;
    endif;
?>
@if($request_status==$ppc_status)
<tr class="text-center" id="RemoveTr<?php echo $row->id?>">
    <td><?php echo $count++?></td>
    <td><?php echo strtoupper($row->order_no)?></td>
    <td>{{CommonHelper::changeDateFormat($row->order_date)}}</td>
    <td>{{CommonHelper::changeDateFormat($row->start_date)}}</td>
    <td>{{CommonHelper::changeDateFormat($row->end_date)}}</td>

    <td>@if($row->type==1) Standard @else Make To Order @endif</td>
    <td>
        @if(!empty($ppc_completion_data->v_date))
            Approved
            <br> <b> {{CommonHelper::changeDateFormat($ppc_completion_data->v_date)}} </b>

        @else
            @if($status==0) Planned @else @if($conversion>0) @if($cost>0) Verified @else Complete @endif @else Release @endif @endif
        @endif
    </td>
    <td>{{ucfirst($row->usernmae)}}</td>

    <td>
        <?php if($view == true):?>
        <button onclick="showDetailModelOneParamerter('production/view_plan?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','View Production Plan')"   type="button" class="btn btn-success btn-xs">View</button>
        <?php endif;?>


        <?php if($view == true):?>
        <button onclick="showDetailModelOneParamerter('production/view_issuence?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','View Production Plan')"   type="button" class="btn btn-success btn-xs">View Issuence</button>
        <?php endif;?>

        <?php if($edit == true && $conversion==0 && $issuence_data==false):?>
        <a href="<?php echo URL::asset('production/edit_production_plane?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
        <?php endif;?>
        <?php if($delete == true  && $conversion==0 && $issuence_data==false):?>
        <button onclick="DeletePlane('<?php echo $row->id?>','<?php echo Session::get('run_company') ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
        <?php endif;?>
        <?php if($issuence == true && $conversion==0):?>
        <button onclick="showDetailModelOneParamerter('production/ppc_issue_item?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Issue Item')"   type="button" class="btn btn-primary btn-xs">Issuance</button>
        <?php endif;?>
        <?php if($return == true && $conversion==0):?>
        <button onclick="showDetailModelOneParamerter('production/material_return?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Material Return')"   type="button" class="btn btn-primary btn-xs">Return</button>
        <?php endif;?>
        <?php if($conversion_rights == true && $conversion==0):?>
        <button onclick="showDetailModelOneParamerter('production/conversion?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Conversion Quantity')"   type="button" class="btn btn-primary btn-xs">Conversion Qty</button>
        <?php endif;?>


        <button onclick="showDetailModelOneParamerter('production/conversion_cost?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Cost')"   type="button" class="btn btn-primary btn-xs hide">Cost</button>

        <?php if($conversion_rights == true):?>
        <button onclick="showDetailModelOneParamerter('production/view_cost?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Conversion Cost')"   type="button" class="btn btn-success btn-xs">Conversion Cost</button>
        <?php endif;?>


    </td>
</tr>
@endif
<?php endforeach;?>
