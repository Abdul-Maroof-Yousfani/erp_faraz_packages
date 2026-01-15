<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;

?>
@extends('layouts.default')

@section('content')
    @include('select2')

    <style>
        * {
            font-size: 12px!important;
            font-family: Arial;
        }
    </style>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
            @include('Purchase.'.$accType.'purchaseMenu')
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Good Issuance Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'pad/updateIssuanceDetail?m='.$m.'','id'=>'cashPaymentVoucherForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="UpdateId" value="<?php echo $id?>">


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="issuanceSection[]" class="form-control requiredField" id="issuanceSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">ISSUANCE NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="is_no" id="is_no" value="{{strtoupper($Issuance->iss_no)}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">ISSUANCE Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" max="" name="iss_date_1" id="iss_date_1" value="<?php echo $Issuance->iss_date ?>" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Region</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField select2" name="region" id="region">
                                                    <option value="">Select Region</option>
                                                    @foreach(CommonHelper::get_all_regions() as $row)
                                                        <option value="{{$row->id}}" <?php if($Issuance->region == $row->id){echo "selected";}?>>{{$row->region_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Job Order</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select  name="joborder" id="joborder" class="form-control requiredField select2">
                                                    <option value="">Select Job Order</option>
                                                    <?php foreach($JobOrder as $Fil): ?>
                                                    <option value="<?php echo $Fil->job_order_no;?>" <?php if($Issuance->joborder == $Fil->job_order_no){echo "selected";}?>> <?php echo $Fil->job_order_no;?> </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField"><?php echo $Issuance->description?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="buildyourform" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createCategoryFormAjax')" class="">Category</a></th>
                                                    <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax/0')" class="">Sub Item</a></th>
                                                    <th class="text-center" >Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" >In Stock Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" >Current Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                                                </tr>
                                                </thead>
                                                <tbody class="addMoreIssuanceDetailRows_1" id="addMoreIssuanceDetailRows_1">
                                                <?php
                                                $Counter=0;
                                                foreach($IssuanceData as $DataFil):
                                                $Counter++;
                                                ?>

                                                <tr id="removeDemandsRows_1_<?php echo $Counter?>">
                                                    <input type="hidden" name="issuanceDataSection_1[]" class="form-control requiredField" id="issuanceDataSection_<?php echo $Counter;?>" value="<?php echo $Counter;?>" />
                                                    <td>
                                                        <select name="category_id_1_<?php echo $Counter?>" id="category_id_1_<?php echo $Counter?>" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField select2">
                                                            <?php echo PurchaseHelper::categoryList($_GET['m'],$DataFil->category_id);?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select onchange="get_detail(this.id,'<?php echo $Counter?>')"  name="sub_item_id_1_<?php echo $Counter?>" id="sub_item_id_1_<?php echo $Counter?>" class="form-control requiredField select2">
                                                            <?php echo PurchaseHelper::subItemList($_GET['m'],'',$DataFil->category_id,$DataFil->sub_item_id);?>
                                                        </select>
                                                    </td>
                                                    <td> <input onkeyup="CurrentQty(this.id,'<?php echo $Counter?>')" step="0.01" type="number" name="qty_1_<?php echo $Counter?>" id="qty_1_<?php echo $Counter?>" class="form-control requiredField" value="<?php echo $DataFil->qty?>" /> </td>
                                                    <td><input class="form-control" readonly type="text" id="stock_qty_1_<?php echo $Counter?>" name="stock_qty_1_<?php echo $Counter?>"/> </td>
                                                    <td class="text-right"><input class="form-control" readonly type="text" id="current_qty_1_<?php echo $Counter?>" name="current_qty_1_<?php echo $Counter?>"/> </td>
                                                    <script !src="">
                                                        $(document).ready(function(){
                                                            //subItemListLoadDepandentCategoryId('category_id_1_<?php echo $Counter?>','<?php echo $DataFil->category_id?>');
                                                            get_detail('sub_item_id_1_<?php echo $Counter?>','<?php echo $Counter?>')
                                                            CurrentQty('stock_qty_1_<?php echo $Counter?>','<?php echo $Counter?>');

                                                        });
                                                    </script>
                                                </tr>
                                                <?php

                                                endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" class="btn btn-sm btn-primary" onclick="addMoreIssuanceDetailRows('1')" value="Add More Demand's Rows" />
                                        <input type="button" onclick="removeDemandsRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="issuanceSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var d = '<?php echo $Counter?>';
            $('.addMoreDemands').click(function (e){
                e.preventDefault();
                d++;
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormDemandVoucher',
                    type: "GET",
                    data: { id:d,m:m},
                    success:function(data) {
                        $('.demandsSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="Demands_'+d+'"><a href="#" onclick="removeDemandsSection('+d+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                    }
                });
            });

            $(".btn-success").click(function(e){
                var demands = new Array();
                var val;
                $("input[name='issuanceSection[]']").each(function(){
                    demands.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of demands) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });
        });
        var x = '<?php echo $Counter?>';
        function addMoreIssuanceDetailRows(id){
            x++;
            //alert(id+' ---- '+x);
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/addMoreIssuanceDetailRows',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {
                    $('.addMoreIssuanceDetailRows_'+id+'').append(data);
                    $('#category_id_1_'+x).select2();
                    $('#sub_item_id_1_'+x).select2();
                    $('#joborder').select2();
                    $('#category_id_1_'+x).focus();
                }
            });
        }

        function removeDemandsRows(id,counter){
            var elem = document.getElementById('removeDemandsRows_'+id+'_'+counter+'');
            elem.parentNode.removeChild(elem);
        }
        function removeDemandsSection(id){
            var elem = document.getElementById('Demands_'+id+'');
            elem.parentNode.removeChild(elem);
        }

        function subItemListLoadDepandentCategoryId(id,value) {
            //alert(id+' --- '+value);
            var arr = id.split('_');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {
                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }

    </script>


    <script>
        function get_detail(id,number) {
            var region   = $('#region').val();
            var category = $('#category_id_1_'+number).val();
            var subitemid = $('#'+id).val();
            // alert(subitemid);
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock',
                type: "GET",
                data: { subitemid:subitemid, region:region, category:category },
                success:function(data) {
                    $('#stock_qty_1_'+number).val(data);
                }
            });
        }

        function CurrentQty(qty,num)
        {
            var quatity = $('#'+qty).val();
            var stock = $('#stock_qty_1_'+num).val();
            var current = stock-quatity;
            $('#current_qty_1_'+num).val(current);

        }
    </script>

    <script>
        function removeDemandsRows(){

            var id=1;
            alert(x);

            if (x > 1)
            {
                //  var elem = document.getElementById('removeDemandsRows_'+id+'_'+x+'');
                //   elem.parentNode.removeChild(elem);

                $('#removeDemandsRows_'+id+'_'+x+'').remove();

                x--;
                '<?php $Counter-1?>';

            }


        }

        function view_history(id)
        {

            var v= $('#sub_item_id_1_'+id).val();


            if ($('#history_1_' + id).is(":checked"))
            {
                if (v!=null)
                {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem?id='+v);
                }
                else
                {
                    alert('Select Item');
                }

            }





        }
    </script>
    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection