<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$Counter = 1;
$paramurl = "pdc/viewJobOrderDetail?m=".$m;
$paramOne = "pdc/viewStockReturnDetail?m=".$m;
$paramThree = "View Stock Return Detail";
$paramFour= url('/purchase/editStockReturn/');
$issuance_type="";
foreach($stock_return as $dataFil):
if($dataFil->issuance_type==1): $issuance_type = "Return With Job Order";
elseif($dataFil->issuance_type==3): $issuance_type = "Return Without Job Order";
elseif($dataFil->issuance_type==4): $issuance_type = "Return Damage Stock";
endif;
$job_order_id = CommonHelper::JobOrderNoData($dataFil->joborder);
?>
<tr class="text-center" id="RemoveTr<?php echo $dataFil->issuance_no ?>">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($dataFil->issuance_no)?></td>
    <td><?php echo CommonHelper::changeDateFormat($dataFil->issuance_date)?></td>
    <td><?php echo ($issuance_type!="")?$issuance_type:""; ?></td>
    <td><?php echo $dataFil->description?></td>
    <td> <a href="#" onclick="showDetailModelOneParamerter('<?= $paramurl ?>','<?= $job_order_id ?>','Job Order')"><i class="entypo-layout"></i> Job Order </a> </td>
    <td>
        <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $dataFil->issuance_no;?>','<?php echo $paramThree?>')"   type="button" class="btn btn-success btn-xs">View</button>
        @if($dataFil->return_status == 1)
            <button type="button" class="btn btn-xs btn-danger" onclick="DeleteStockReturn('<?php echo $dataFil->issuance_no ?>')" id="BtnDelete<?php echo $dataFil->stock_return_id ?>">Delete</button>
            <button type="button" class="btn btn-xs btn-success" onclick="ApprovedStockReturn('<?php echo $dataFil->stock_return_id ?>')" id="BtnApprove<?php echo $dataFil->stock_return_id ?>"> Approve</button>
            <a id="BtnEdit<?php echo $dataFil->stock_return_id ?>" href='<?php echo $paramFour.'/'.$dataFil->stock_return_id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs">Edit</a>
        @endif
        <?php if(Auth::user()->id == 152 && $dataFil->return_status == 2):?>
        <button type="button" class="btn btn-xs btn-danger" onclick="DeleteStockReturn('<?php echo $dataFil->issuance_no ?>')" id="BtnDelete<?php echo $dataFil->stock_return_id ?>">Delete</button>
        <?php endif;?>
    </td>
</tr>
<?php endforeach;?>

    <script>
        function DeleteStockReturn(issuance_no)
        {
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';

                //$('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
                $.ajax({
                    url: '/pdc/DeleteStockReturn',
                    type: 'Get',
                    data: {issuance_no:issuance_no, m:m},

                    success: function (response) {
                        alert(response);
                        $('#RemoveTr'+response).remove();
                    }
                });
            }
            else {}
        }

        function ApprovedStockReturn(stock_return_id){
            var m = '<?php echo $_GET['m'];?>';
            $('#BtnApprove'+stock_return_id).prop('disabled',true);
            $.ajax({
                url: '<?php echo url('/')?>/pdc/ApprovedStockReturn',
                type: "GET",
                data: { stock_return_id:stock_return_id,m:m},
                success:function(data) {
                    alert("Approved");
                    $('#BtnApprove'+stock_return_id).css('display','none');
                    $('#BtnDelete'+stock_return_id).css('display','none');
                    $('#BtnEdit'+stock_return_id).css('display','none');
                }
            });
        }

    </script>

