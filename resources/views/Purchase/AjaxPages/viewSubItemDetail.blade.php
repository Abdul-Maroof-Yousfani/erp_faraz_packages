<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>





@include('select2');
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                    @include('Purchase.'.$accType.'purchaseMenu')
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">View Sub Item</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <table id="buildyourform" class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Main Category</th>
                                                        <th class="text-center">Sub Item Name :</th>
                                                        <th class="text-center">UOM </th>
                                                        <th class="text-center">Pack Size</th>
                                                        <th class="text-center">Rate </th>
                                                        <th class="text-center">Description </th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Opening Quantity</th>
                                                        <th class="text-center">Opening Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="TrAppend">
                                                    <tr>
                                                        <td><?php  echo CommonHelper::get_category_name($sub_item->main_ic_id); ?></td>
                                                        <td><?php  echo $sub_item->sub_ic ?></td>
                                                        <td><?php  echo CommonHelper::get_uom_name($sub_item->uom);  ?></td>

                                                        <td><?php  echo $sub_item->pack_size ?></td>
                                                        <td><?php  echo $sub_item->rate ?></td>
                                                        <td><?php  echo $sub_item->description ?></td>

                                                        <td><?php  echo CommonHelper::get_name_demand_type($sub_item->itemType); ?></td>
                                                        <td><?php  echo $sub_item->open_qty ?></td>
                                                        <td><?php  echo $sub_item->open_qty*$sub_item->rate;//echo $sub_item->open_val ?></td>

                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".btn-success").click(function(e){
            var subItem = new Array();
            var val;
            //$("input[name='chartofaccountSection[]']").each(function(){
            subItem.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of subItem) {

                jqueryValidationCustom();
                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
        });
    });
</script>
<script type="text/javascript">

    $('.select2').select2();
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

