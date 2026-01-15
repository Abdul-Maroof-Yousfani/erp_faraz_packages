<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$m = Session::get('run_company');

?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="email">Category</label>
                                                <select name="category_id" id="category_id" onchange="get_category_wise_sub_category()" class="form-control  select2">
                                                    <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="email">Sub Category</label>
                                                <select name="sub_category_id" id="sub_category_id" class="form-control  select2" width="183px;" onchange="subItemGetAjax()">

                                                </select>
                                                <span id="SubCatLoading"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="email">Items</label>
                                                <select name="subItems" id="subItems" class="form-control  select2" width="183px;">

                                                </select>
                                                <span id="ItemLoading"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label for="">Customer</label>
                                            <select name="CustomerId" id="CustomerId" class="form-control select2">
                                                <option value="">Select Customer</option>
                                                <?php foreach($Customers as $Fil):?>
                                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                <?php endforeach;?>

                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default" onclick="BookDayList();">Submit</button>
                                            </div>
                                        </div>

                                    </div>
                                    <span class="subHeadingLabelClass">Top Five Sales Report</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <span id="AjaxDataHere"></span>
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
            $('.select2').select2();
        });

        function get_category_wise_sub_category()
        {
            var category_id = $('#category_id').val();
            if(category_id > 0)
            {
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/get_category_wise_sub_category',
                    type: "GET",
                    data: { category_id:category_id},
                    beforeSend: function() {
                        $('#SubCatLoading').html('<div class="loader"></div>');
                    },
                    success:function(data) {
                        $('#SubCatLoading').html('');
                        $('#sub_category_id').html(data);
                        $('#subItems').html('');
                    }
                });
            }
            else
            {
                $('#subItems').html('');
                $('#sub_category_id').html('');
            }
        }
        function subItemGetAjax()
        {

            var CategoryId = $('#category_id').val();
            var SubCategoryId = $('#sub_category_id').val();
            if(CategoryId > 0 && SubCategoryId > 0)
            {
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/get_sub_item_all_ajax',
                    type: "GET",
                    data: {CategoryId:CategoryId,SubCategoryId:SubCategoryId},
                    beforeSend: function() {
                        $('#ItemLoading').html('<div class="loader"></div>');
                    },
                    success:function(data) {
                        $('#ItemLoading').html('');
                        $('#subItems').html(data);
                    }
                });
            }
            else
            {
                $('#subItems').html('');
            }

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

        function BookDayList(){
            //if (loading == false) {
            var category = $('#category_id').val();
            var sub_category = $('#sub_category_id').val();
            var subItems = $('#subItems').val();
            var CustomerId = $('#CustomerId').val();

            var m = '<?php echo $_GET['m']?>';
            if (subItems != "" || CustomerId != "") {
                $('#AjaxDataHere').html('<div class="loader"></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/sdc/getTopFiveSalesReport',
                    method: 'GET',
                    data: {subItems: subItems, CustomerId: CustomerId, m: m},
                    error: function () {
                        alert('error');
                    },
                    success: function (response)
                    {
                        $('#AjaxDataHere').html(response);
                    }
                });
            }
            else {
                $('#AjaxDataHere').html('');

            }
        }

    </script>

    <script type="text/javascript">
        function viewSubItemList(){
            if (loading == false) {
                loading = true;
                $('#viewSubItemList').html('<tr><td colspan="7"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/viewSubItemList',
                    type: "GET",
                    data:{m:m},
                    success:function(data) {
                        setTimeout(function(){
                            $('#viewSubItemList').html(data);
                        },1000);
                        loading = false;
                    }
                });
            }
            else {
                alert("Wait Loading");
            }
        }
        //viewSubItemList();
    </script>
@endsection