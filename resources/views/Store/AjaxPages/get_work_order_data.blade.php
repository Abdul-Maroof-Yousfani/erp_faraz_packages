<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\StoreHelper;
$UserId = Auth::user()->id;

$view=ReuseableCode::check_rights(310);
$issue_item=ReuseableCode::check_rights(311);
$create_return=ReuseableCode::check_rights(312);
$delete=ReuseableCode::check_rights(313);
$create_pi=ReuseableCode::check_rights(314);

$FromDate = $_GET['FromDate'];
$ToDate = $_GET['ToDate'];
$WoType = $_GET['WoType'];

$Counter = 1;
$paramOne = "stdc/viewIssuanceDetail?m=".Session::get('run_company');
$pi = "stdc/viewIssuanceDetail?m=".Session::get('run_company').'&&Pi=1';
$paramThree = "View Work Order Detail";
        if($WoType == 'all'):
            $data = DB::Connection('mysql2')->table('product_creation')->where('status',1)->whereBetween('voucher_date',[$FromDate,$ToDate])->get();
        elseif($WoType == 1):
            $data =array();
        elseif($WoType == 2):
            $data = DB::Connection('mysql2')->table('product_creation')->where('status',0)->whereBetween('voucher_date',[$FromDate,$ToDate])->get();
        else:
        endif;
if(count($data) > 0):
foreach($data as $row):
$count=   ReuseableCode::check_issuence_entry($row->id);
$qty=StoreHelper::get_sum_qty('product_creation_data',$row->id,'qty');
$net_amount=StoreHelper::get_sum_qty('product_creation_data',$row->id,'net_amount');

$check=DB::Connection('mysql2')->table('product_creation_data')->where('status',1)->where('master_id',$row->id)->where('pi_no','=',null)->count();


?>
<tr class="text-center" id="remove<?php echo $row->voucher_no?>">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($row->voucher_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($row->voucher_date);?></td>
    <td>{{CommonHelper::get_supplier_name($row->supplier_id)}}</td>

    <td><?php echo $qty?></td>
    <td><?php echo number_format($net_amount,2)?></td>

    <td>
        <?php if($view == true):?>
            <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $row->id;?>','<?php echo $paramThree?>')"   type="button" class="btn btn-success btn-xs">View</button>
        <?php endif;?>
            {{--Edit Code --}}
            <?php if($count == 0):?>
            <a href="<?php echo URL::asset('store/editIssuanceForm?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
            <?php endif;?>
            {{--Edit Code --}}
            <?php

         //  $issue_data= ReuseableCode::get_issuence($row->voucher_no);
            ?>

        @if($row->wo_status==0)
            <a target="_blank" @if ($check==0): onclick="alert('Complete')" @else href="{{url('store/issuence_against_product?id='.$row->id)}}"   @endif  type="button" class="btn btn-primary btn-xs"> @if($count==0) <?php if($issue_item == true):?> Issue Items <?php endif;?> @else  <?php if($create_return == true):?> Create Return <?php endif;?> @endif</a>
            @if($count==0)
                <?php if($delete == true):?>
                <button  onclick="delete_issue('{{$row->voucher_no}}')"  type="button" class="btn btn-danger btn-xs">Delete</button>
                <?php endif;?>
            @endif

            @if($count>0)
                <?php if($create_pi == true):?>
                <button onclick="showDetailModelOneParamerter('<?php echo $pi?>','<?php echo $row->id;?>','<?php echo $paramThree?>')"   type="button" class="btn btn-success btn-xs">Create PI</button>
                <?php endif;?>
            @endif
        @endif
    </td>
</tr>
<?php endforeach;
        else:?>
    <tr class="text-center">
        <td colspan="7"><strong style="font-size: 18px;" class="text-danger">No Data Found....</strong></td>
    </tr>
<?php endif;?>
