<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$FromDate = $_GET['FromDate'];
$ToDate = $_GET['ToDate'];
$m = $_GET['m'];

$view=ReuseableCode::check_rights(42);
$edit=ReuseableCode::check_rights(43);
$delete=ReuseableCode::check_rights(44);
?>

<?php

$MasterData = DB::Connection('mysql2')->table('stock_transfer')->where('status', '=', 1)->whereBetween('tr_date',[$FromDate,$ToDate])->orderBy('id', 'desc')->get();


$Counter = 1;
$paramOne = "stdc/viewStockTransferDetail?m=".$m;
$paramThree = "View Issuance Detail";

foreach($MasterData as $row):
$edit_url= url('/store/editStockTransferForm/'.$row->id.'/'.$row->tr_no.'?m='.$m);
?>
<tr class="text-center" id="RemoveTr<?php echo $row->id?>">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($row->tr_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($row->tr_date);?></td>
    <td><?php echo strtoupper($row->description);?></td>
    <td class="{{$row->id}}">@if($row->tr_status==1) Pending @else Approve @endif</td>
    <td>
        @if($view==true)
            <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $row->tr_no;?>','View Stock Transfer Detail')"   type="button" class="btn btn-success btn-xs">View</button>
        @endif
        @if($edit==true)
            <a href="<?php echo $edit_url?>" type="button" class="btn btn-xs btn-primary">Edit</a>
        @endif
        @if($delete==true)
            <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $row->id?>" onclick="DeleteStockTransfer('<?php echo $row->id?>','<?php echo $row->tr_no?>','<?php echo $row->tr_status?>')">Delete</button>
        @endif
    </td>
</tr>
<?php endforeach;?>
