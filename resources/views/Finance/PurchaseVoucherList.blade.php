<?php
use App\Helpers\CommonHelper;

$SupplierId = '';

if(isset($_GET['SupplierId'])):
    $SupplierId = $_GET['SupplierId'];
else:
    $SupplierId = '';
endif;


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
                                    <span class="subHeadingLabelClass">View Purchase Voucher List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">

                                <?php
                                    $get_all_suppliers = CommonHelper::get_all_supplier();
                                ?>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                     
                                        <select name="supplier" id="supplier" class="form-control select2">
                                            <option value="0">Select Supplier</option>
                                            @foreach($get_all_suppliers as $get_all_supplier)
                                                <option value="{{$get_all_supplier->id}}" <?php if($get_all_supplier->id == $SupplierId){echo "selected";}?>>
                                                    {{ $get_all_supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <input type="button" onclick="Generate()" class="btn btn-sm btn-success" id="generate" value="Generate" >
                                    </div>

                                </div>



                                <div id="dataAjax"></div>

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

        function Generate()
        {
            $("#data").hide();
            $('#dataAjax').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?php echo $_GET['m'];?>';
            supplier_id = $("#supplier").val();
            $.ajax({
                url: '<?php echo url('/')?>/DataSortBySupplier',
                type: "GET",
                data: {supplier_id:supplier_id, m:m,class:'table'},
                success:function(data) {
                    $("#dataAjax").show();
                    $("#dataAjax").html(data);
                }
            });
        }

        function DeletePvActivity(pv_id)
        {
            if (confirm('Are you sure you want to delete this Voucher...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeletePVoucherActivity',
                    type: "GET",
                    data: {pv_id:pv_id},
                    success:function(data) {
                        alert('Successfully Deleted');
                        $("#tr"+pv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }

        }
    </script>

@endsection
