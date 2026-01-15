<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\SaleHelper;
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Credit Sale Voucher Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'sad/addCreditSaleVoucherDetail?m='.$m.'','id'=>'addCreditSaleVoucherDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="creditSaleSection[]" class="form-control requiredField" id="creditSaleSection" value="1" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Invoice Date.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="invoice_date_1" id="invoice_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">DC No.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="DC No" name="dc_no_1" id="dc_no_1" value="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Vehicle No.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="Vehicle No" name="vehicle_no_1" id="vehicle_no_1" value="" />
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="sf-label">Customer Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" name="customer_name_id_1" id="customer_name_id_1">
                                                        <option value="">Select Customer</option>
                                                        @foreach($Customers as $key => $y)
                                                            <option value="{{ $y->id}}">{{ $y->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Credit Account Head</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" name="credit_acc_id_1" id="credit_acc_id_1">
                                                        <option value="">Select Account Head</option>
                                                        @foreach($accounts as $key => $y1)
                                                            <option value="{{ $y1->id}}">{{ $y1->code .' ---- '. $y1->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Invoice Against Discount</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="Invoice Against Discount" name="invoice_against_discount_1" id="invoice_against_discount_1" value="" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Description</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <textarea name="main_description_1" id="main_description_1" rows="2" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="well">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive">
                                                                    <table id="buildyourform" class="table table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center">Category <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Sub Item <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Description <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Price <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Qty in kg <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:100px;">Action</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="addMoreCreditSaleDetailRows_1" id="addMoreCreditSaleDetailRows_1">
                                                                        <input type="hidden" name="creditSaleDataSection_1[]" class="form-control requiredField" id="creditSaleDataSection_1" value="1" />
                                                                        <tr>
                                                                            <td>
                                                                                <select name="category_id_1_1" id="category_id_1_1" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField">
                                                                                    <?php echo SaleHelper::categoryList($_GET['m'],'0');?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="sub_item_id_1_1" id="sub_item_id_1_1" class="form-control requiredField">
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="description_1_1" id="description_1_1" placeholder="Description" class="form-control requiredField" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="price_1_1" id="price_1_1" placeholder="Price" onkeyup="updateAmount(this.id,1,1)" class="form-control requiredField" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="qty_1_1" id="qty_1_1" placeholder="Quantity" onkeyup="updateAmount(this.id,1,1)" onchange="checkQuantityinStock(this.id,1,1,'category_id_1_1','sub_item_id_1_1')" class="form-control requiredField" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" readonly name="amount_1_1" id="amount_1_1" placeholder="Amount" class="form-control requiredField" />
                                                                            </td>
                                                                            <td class="text-center">---</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                    <input type="button" class="btn btn-sm btn-primary" onclick="addMoreCreditSaleDetailRows('1')" value="Add More Credit Sale's Rows" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="creditSaleSection"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                        <input type="button" style="display: none;" class="btn btn-sm btn-primary addMoreCreditSale" value="Add More Credit Sale's Section" />
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var d = 1;
            $('.addMoreCreditSale').click(function (e){
                e.preventDefault();
                d++;
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/smfal/makeFormCreditSaleVoucher',
                    type: "GET",
                    data: { id:d,m:m},
                    success:function(data) {
                        $('.creditSaleSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="Demands_'+d+'"><a href="#" onclick="removeDemandsSection('+d+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                    }
                });
            });

            $(".btn-success").click(function(e){
                var creditSales = new Array();
                var val;
                $("input[name='creditSaleSection[]']").each(function(){
                    creditSales.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of creditSales) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });
        });
        var x = 1;
        function addMoreCreditSaleDetailRows(id){
            x++;
            //alert(id+' ---- '+x);
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/smfal/addMoreCreditSaleDetailRows',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {
                    $('.addMoreCreditSaleDetailRows_'+id+'').append(data);
                }
            });
        }

        function removeCreditSaleRows(id,counter){
            var elem = document.getElementById('removeCreditSaleRows_'+id+'_'+counter+'');
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
                url: '<?php echo url('/')?>/smfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {
                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }

        function updateAmount(inputId,id,counter) {
            //alert(inputId+'   ---   '+id+'   ---   '+counter);
            var price = $('#price_'+id+'_'+counter+'').val();
            var qty = $('#qty_'+id+'_'+counter+'').val();
            if (price < 0) {
                alert('Negative Values Not allowed');
                $('#price_'+id+'_'+counter+'').val('');
                return false;
            }
            if (qty < 0) {
                alert('Negative Values Not allowed');
                $('#qty_'+id+'_'+counter+'').val('');
                return false;
            }
            var totalAmount = parseInt(price) * parseInt(qty);
            $('#amount_'+id+'_'+counter+'').val(totalAmount);
            //alert(price+'   ---   '+qty);
        }

        function checkQuantityinStock(inputId,id,counter,categoryId,itemId) {
            var categoryIdValue = $('#'+categoryId+'').val();
            var itemIdValue = $('#'+itemId+'').val();
            var inputIdValue = $('#'+inputId+'').val();
            if(itemIdValue === null){
                alert('Please any one select Sub Item');
                $('#'+inputId+'').val('');
                return false;
            }
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/sa/checkQuantityinStock',
                type: "GET",
                data: { m:m,itemIdValue:itemIdValue,categoryIdValue:categoryIdValue,inputIdValue:inputIdValue},
                success:function(data) {
                    if(parseInt(inputIdValue) <= parseInt(data)){
                    }else{
                        alert(data+' \n Total Stock Quantity = ('+data+') \n Total Sold Quantity = ('+inputIdValue+') \n Please assigned correct value and insert data \n Thank You!');
                        $('#'+inputId+'').val('');
                        $('#amount_'+id+'_'+counter+'').val('');
                    }
                }
            });
        }

    </script>
@endsection