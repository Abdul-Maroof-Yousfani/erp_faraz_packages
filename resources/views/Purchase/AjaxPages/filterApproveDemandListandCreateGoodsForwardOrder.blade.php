<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];
$paramOne = $_GET['paramOne'];
$parentCode = $_GET['parentCode'];
if(empty($paramOne)){
    $branchsList = DB::select('select `id`,`name` from `company`');
}else{
    $branchsList = DB::select('select `id`,`name` from `company` where `id` = '.$paramOne.'');
}
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <?php
                foreach ($branchsList as $row){
                    echo $row->name.'&nbsp;&nbsp;&nbsp; ---- &nbsp;&nbsp;&nbsp;'.$row->id.'<br />';
                ?>
                <div class="well">
                    <?php foreach ($branchsList as $row2){?>
                    <?php
                    CommonHelper::companyDatabaseConnection($row->id);
                    $approveDemandVoucherListandCreateGoodsForwardOrder = \DB::table("demand_data")
                        ->select("demand_data.demand_no","demand.sub_department_id","demand_data.demand_date","demand_data.category_id","demand_data.sub_item_id","demand_data.description","demand_data.id","demand.slip_no","demand.demand_type","demand_data.qty","demand_data.demand_send_type",
                            \DB::raw("(SELECT SUM(goods_forward_qty) as goods_forward_qty FROM goods_forward_data
                        WHERE goods_forward_data.goods_forward_qty != 0 and goods_forward_data.category_id = demand_data.category_id
                        and goods_forward_data.sub_item_id = demand_data.sub_item_id
                        and goods_forward_data.demand_no = demand_data.demand_no
                        ) as goods_forward_qty"))
                        ->join('demand','demand_data.demand_no','=','demand.demand_no')
                        ->whereBetween('demand.demand_date',[$fromDate,$toDate])
                        ->where(['demand.status' => '1','demand.demand_status' => '2'])
                        ->whereIn('demand_data.demand_send_type', [$row2->id])
                        ->where('demand_data.goods_forward_status','!=','2')
                        ->get();
                    CommonHelper::reconnectMasterDatabase();
                    ?>
                    <div class="panel">
                        <div class="panel-body">
                            <?php echo Form::open(array('url' => 'pad/createGoodsForwardOrderDetailForm?m='.$m.'&&parentCode='.$parentCode.'&&pageType=add#SFR','id'=>'createGoodsForwardOrderDetailForm_'.$row->id.''));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="branchId" value="<?php echo $row->id;?>">
                            <?php echo $row2->name.'&nbsp;&nbsp;&nbsp; ---- &nbsp;&nbsp;&nbsp;'.$row2->id;?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center"><input type="checkbox" style="display: none" name="checkedAll_<?php echo $row->id?>" id="checkedAll_<?php echo $row->id?>" class="checkedAll_<?php echo $row->id?>" /></th>
                                            <th class="text-center">Demand No.</th>
                                            <th class="text-center">Slip No.</th>
                                            <th class="text-center">Demand Date</th>
                                            <th class="text-center">Demand Type</th>
                                            <th class="text-center">Sub Department</th>
                                            <th class="text-center">Category Name</th>
                                            <th class="text-center">Item Name</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Demand Qty.</th>
                                            <th class="text-center">Goods Forward Qty.</th>
                                            </thead>
                                            <tbody id="filterDemandVoucherList">
                                            <?php
                                            $counter = 1;
                                            if(count($approveDemandVoucherListandCreateGoodsForwardOrder) == 0){}else{
                                            foreach ($approveDemandVoucherListandCreateGoodsForwardOrder as $row1){
                                            if($row1->goods_forward_qty == $row1->qty){}else{
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $counter;?></td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="checkAll_<?php echo $row->id?>_<?php echo $row1->id?>"
                                                           class="checkSingle_<?php echo $row->id?>"
                                                           id="chekedItemWise_<?php echo $row->id?>_<?php echo $row1->sub_item_id?>"
                                                           value="<?php echo $row1->demand_no?>" onclick="checkCheckedBox(this.id,'<?php echo $row->id;?>','<?php echo $row1->id;?>')">
                                                </td>
                                                <td class="text-center"><?php echo $row1->demand_no;?></td>
                                                <td class="text-center"><?php echo $row1->slip_no;?></td>
                                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row1->demand_date);?></td>
                                                <td class="text-center">
                                                    <?php if($row1->demand_type == 1){echo 'Office Use';}else if($row1->demand_type == 2){echo 'For Sale';}?>
                                                </td>
                                                <td><?php echo $row1->sub_department_id;?></td>
                                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'category','main_ic',$row1->category_id);?></td>
                                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id);?></td>
                                                <td><?php echo $row1->description;?></td>
                                                <td class="text-center">
                                                    <?php echo $row1->qty;?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $row1->goods_forward_qty;?>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            $counter++;}
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                if(count($approveDemandVoucherListandCreateGoodsForwardOrder) == 0){}else{
                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                </div>
                                <?php }?>
                            </div>
                            <?php echo Form::close();?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php
                    //print_r($demandSendTypeArray);
                    //if(count($approveDemandVoucherListandCreateGoodsForwardOrder) == 0){}else{
                ?>

                <?php
                    //}
                ?>
                    <div class="lineHeight">&nbsp;</div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(".checkedAll_<?php echo $row->id?>").change(function(){
                                if(this.checked){
                                    $(".checkSingle_<?php echo $row->id?>").each(function(){
                                        this.checked=true;
                                    })
                                }else{
                                    $(".checkSingle_<?php echo $row->id?>").each(function(){
                                        this.checked=false;
                                    })
                                }
                            });

                            $(".checkSingle_<?php echo $row->id?>").click(function () {
                                if ($(this).is(":checked")){
                                    var isAllChecked = 0;
                                    $(".checkSingle_<?php echo $row->id?>").each(function(){
                                        if(!this.checked)
                                            isAllChecked = 1;
                                    })
                                    //if(isAllChecked == 0){ $(".checkedAll_<?php echo $row->id?>").prop("checked", true); }
                                }else {
                                    $(".checkedAll_<?php echo $row->id?>").prop("checked", false);
                                }
                            });
                        });
                    </script>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function checkCheckedBox(id,sIdOne,sIdTwo) {
        if ($('#'+id+':checked').length <= 1){
        }else{
            alert("Please select at least one checkbox Same Item.");
            $("input[name='checkAll_"+sIdOne+"_"+sIdTwo+"']:checkbox").prop('checked', false);
        }

    }
</script>