<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Item Master List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group ">
                                                                <label for="email">Category</label>
                                                                <select name="category_id" id="category_id" onchange="get_data()" class="form-control  select2">
                                                                    <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <span id="dataa"></span>
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
    </div>
    </div>
    <script type="text/javascript">
        function DeleteItemMaster(ItemMasterId)
        {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to delete this recored...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/purchase/deleteItemMaster',
                    type: "GET",
                    data: {ItemMasterId:ItemMasterId,},
                    success:function(data) {
                        //alert(data); return false;
                        alert('Successfully Deleted');
                        $(".tr"+ItemMasterId).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }

        $( document ).ready(function() {
           $('#category_id').select2();
        });

        function get_data()
        {
            var category=$('#category_id').val();
            $('#dataa').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div>');
            $.ajax({
                url: '<?php echo url('purchase/item_master_list')?>',
                type: "GET",
                data: { category:category},
                success:function(data) {
             
                    $('#dataa').html(data);
                }
            });
        }
    </script>
@endsection