<?php

$m = Session::get('run_company');
$EditId = $_GET['edit_id'];
$Master = DB::Connection('mysql2')->table('product_creation')->where('id',$EditId)->first();
$Detail = DB::Connection('mysql2')->table('product_creation_data')->where('master_id',$EditId)->get();


use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;


?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    @include('number_formate')


    <style>
        * {
            font-size: 12px!important;
            font-family: Arial;
        }
        .select2 {
            width: 100%;
        }
    </style>



    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Work Order Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">

                    <?php echo Form::open(array('url' => 'stad/updateIssuanceDetail?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="EditId" value="<?php echo $EditId?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="">
                                        <div class="">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Voucher No</label>
                                                <input type="text" readonly class="form-control" value="<?php echo $Master->voucher_no?>" name="VoucherNo" id="VoucherNo">
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Voucher Date</label>
                                                <input type="date" class="form-control" id="voucher_date" name="voucher_date" value="<?php echo $Master->voucher_date?>">
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Supplier</label>
                                                <select name="supplier_id" id="supplier_id" class="form-control requiredField">
                                                    <option value="">Select Supplier</option>
                                                    <?php foreach(CommonHelper::get_all_supplier() as $Fil): ?>
                                                    <option value="<?php echo $Fil->id?>" <?php if($Master->supplier_id == $Fil->id): echo "selected"; endif;?>><?php echo $Fil->name?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>


                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="description_1" id="description_1" rows="2" cols="50" style="resize:none;" class="form-control requiredField"><?php echo $Master->desc?></textarea>
                                            </div>








                                        </div>
                                    </div>

                                </div>
                                <div class="lineHeight">&nbsp;</div> <div class="lineHeight">&nbsp;</div> <div class="lineHeight">&nbsp;</div>

                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div class="text-right right">
                                            <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                        </div>

                                        <div style="text-align: center" class="table-responsive  text-center" id="">

                                            <table style="width: 90%;margin: auto" class="table table-bordered well">
                                                <thead>

                                                <tr>
                                                    <th  class="text-center">Products</th>
                                                    <th style="width: 150px" class="text-center">UOM</th>
                                                    <th style="width: 150px"  class="text-center" >Make QTY <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th  class="text-center">Make Type</th>
                                                    <th style="width: 200px"  class="text-center" >Making Per PCS Cost <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th style="width: 200px"  class="text-center" >Net Cost <span class="rflabelsteric"><strong>*</strong></span></th>


                                                </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                <?php
                                                $Counter = 1;
                                                        $TotNetAmount = 0;
                                                foreach($Detail as $Dfil):?>
                                                <tr id="" class="RemoveRows<?php echo $Counter?> AutoNo" >
                                                    <td>
                                                        <?php
                                                        $SubItem = DB::Connection('mysql2')->table('subitem')->where('id',$Dfil->product_id)->select('sub_ic','uom')->first();
                                                            $Uom = CommonHelper::only_uom_nam_by_item_id($SubItem->uom);
                                                        ?>
                                                        <input ondblclick="clear_fiel(this.id)"  onchange="get_detail(this.id,'<?php echo $Counter?>')" type="text" class="form-control sam_jass" name="sub_ic_des[]" id="item_<?php echo $Counter?>" value="<?php echo $SubItem->sub_ic?>">
                                                        <input type="hidden" class="requiredField" name="item_id[]" id="sub_<?php echo $Counter?>" value="<?php echo $Dfil->product_id;?>">
                                                    </td>


                                                    <td>
                                                        <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id<?php echo $Counter?>" value="<?php echo $Uom?>">
                                                    </td>
                                                    <td>
                                                        <input onblur="calc('{{$Counter}}')" onkeyup="calc('{{$Counter}}')"  type="text" class="form-control requiredField" name="qty[]" id="qty<?php echo $Counter?>" value="<?php echo $Dfil->qty?>">
                                                    </td>

                                                    <td>
                                                        <select name="maketype[]" id="maketype1" class="form-control requiredField">
                                                            <option value="">Select</option>
                                                            <option value="1" <?php if($Dfil->maketype == 1): echo "selected"; endif;?>>Cutting</option>
                                                            <option value="2" <?php if($Dfil->maketype == 2): echo "selected"; endif;?>>Welding</option>
                                                            <option value="3" <?php if($Dfil->maketype == 3): echo "selected"; endif;?>>Machining</option>
                                                            <option value="4" <?php if($Dfil->maketype == 4): echo "selected"; endif;?>>Thread Making</option>
                                                            <option value="5" <?php if($Dfil->maketype == 5): echo "selected"; endif;?>>Jointing</option>
                                                            <option value="6" <?php if($Dfil->maketype == 6): echo "selected"; endif;?>>APS</option>
                                                            <option value="7" <?php if($Dfil->maketype == 7): echo "selected"; endif;?>>Galvanise</option>
                                                            <option value="8" <?php if($Dfil->maketype == 8): echo "selected"; endif;?>>Service & Marking</option>
                                                            <option value="9" <?php if($Dfil->maketype == 9): echo "selected"; endif;?>>Service</option>
                                                            <option value="10" <?php if($Dfil->maketype == 10): echo "selected"; endif;?>>Marking</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input onblur="calc('{{$Counter}}')" onkeyup="calc('{{$Counter}}')" type="text" class="form-control  requiredField" name="amount[]" id="amount<?php echo $Counter?>" value="<?php echo $Dfil->amount?>">
                                                    </td>

                                                    <td>
                                                        <input readonly type="text" class="form-control amount requiredField" name="net_amount[]" id="net_amount<?php echo $Counter?>" value="<?php echo $Dfil->net_amount?>">
                                                    </td>
                                                    <?php if($Counter > 1):?>
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-danger" id="BtnRemove<?php echo $Counter?>" onclick="RemoveSection('<?php echo $Counter?>')">Remove</button>
                                                    </td>
                                                    <?php else:?>
                                                    <td class="text-center">- - -</td>
                                                    <?php endif;?>

                                                </tr>
                                                <?php
                                                        $TotNetAmount+=$Dfil->net_amount;
                                                $Counter++;
                                                endforeach;?>
                                                </tbody>
                                                <tr>
                                                    <td colspan="5">Total</td>
                                                    <td style="font-weight: bold;font-size: large!important;" colspan="1">  <input readonly type="text" class="form-control requiredField" name="" id="total" value="<?php echo number_format($TotNetAmount,2);?>"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="demandsSection"></div>
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
        $(document).ready(function(){
            $('#supplier_id').select2();
            $('#total').number(true,2);
        });
        var Counter = '<?php echo $Counter?>';


        function AddMoreDetails()
        {
            Counter++;
            $('#AppnedHtml').append(
                    '<tr class="RemoveRows'+Counter+'  AutoNo">' +
                    '<td>' +
                    '<input type="text" onchange="get_detail(this.id,'+Counter+')" class="form-control sam_jass" name="sub_ic_des[]" id="item_'+Counter+'">' +
                    '<input type="hidden" class="requiredField" name="item_id[]" id="sub_'+Counter+'">'+
                    '<td>' +
                    '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'">' +
                    '</td>' +
                    '<td><input onblur="calc('+Counter+')" onkeyup="calc('+Counter+')"  type="text" class="form-control requiredField" name="qty[]" id="qty'+Counter+'"></td>'+
                    '</td>' +
                    ' <td><select name="maketype[]" id="maketype'+Counter+'" class="form-control requiredField"><option value="">Select</option><option value="1">Cutting</option><option value="2">Welding</option><option value="3">Machining</option><option value="4">Thread Making</option>'+
                    '<option value="5">Jointing</option> <option value="6">APS</option><option value="7">Galvanise</option><option value="8">Service & Marking</option>'+
                    '<option value="9">Service</option><option value="10">Marking</option></select>'+
                    '</td>'+
                    '<td><input onblur="calc('+Counter+')" onkeyup="calc('+Counter+')" type="text" class="form-control requiredField" name="amount[]" id="amount'+Counter+'"></td>'+
                    '<td><input readonly  type="text" class="form-control requiredField amount" name="net_amount[]" id="net_amount'+Counter+'"></td>'+
                    '<td>' +
                    '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')">Remove</button>' +
                    '</td>' +
                    '</tr>');
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);


            $('#category_'+Counter).select2();
            $('#subcategory_'+Counter).select2();
            var AutoCount = 1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).html(AutoCount);
            });




            $('.sam_jass').bind("enterKey",function(e){


                $('#items').modal('show');
            });
            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKey");
                }
            });


            $(".amount").keyup(function(){
                var total=0;
                $(".amount").each(function(){

                    total+=+  $(this).val();
                });
                $('#total').val(total);
            });
        }
        function RemoveSection(Row) {
//            alert(Row);
            $('.RemoveRows' + Row).remove();
            $(".AutoCounter").html('');
            var AutoCount = 1;
            $(".AutoCounter").each(function () {
                AutoCount++;
                $(this).html(AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }


        function clear_fiel(id)
        {
            $('#'+id).prop('readonly', false);
            $('#'+id).val('');

        }

        $('.sam_jass').bind("enterKey",function(e){


            $('#items').modal('show');
            e.preventDefault();

        });
        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");
                e.preventDefault();

            }

        });


        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        $(function() {



            $(".btn-success").click(function(e){
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if(validate == 0){

                        $('#cashPaymentVoucherForm').submit();
                    }
                    else
                    {
                        return false;
                    }
                }

            });
        });








    </script>


    <script>
        function get_detail(id,number)
        {
            var item=$('#'+id).val();


            $.ajax({
                url:'{{url('/pdc/get_data')}}',
                data:{item:item},
                type:'GET',
                success:function(response)
                {

                    var data=response.split(',');
                    $('#uom_id'+number).val(data[0]);
                    $('#last_ordered_qty'+number).val(data[1]);
                    $('#last_received_qty'+number).val(data[2]);
                    $('#closing_stock'+number).val(data[3]);

                }
            })



        }

    </script>

    <script>




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

        function get_stock(warehouse,number)
        {
            var warehouse=$('#'+warehouse).val();
            var item=$('#sub_'+number).val();
            var batch_code='';

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data)
                {
                    $('#batch_code'+number).html(data);
                }
            });
        }

        function ApplyAll(number)
        {
            var count =$('#id_count').val();
            if (number==1)
            {
                for (i=1; i<=count; i++)
                {
                    var selectedVal = $('#warehouse'+number).val();
                    $('.ClsAll').val(selectedVal);
                    get_stock('warehouse'+i,i);
                }
            }
        }

        function get_stock_qty(warehouse,number)
        {


            var warehouse=$('#warehouse'+number).val();
            var item=$('#sub_'+number).val();
            var batch_code=$('#batch_code'+number).val();


            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data)
                {

                    //   $('#batch_code'+number).html(data);

                    data=data.split('/');
                    $('#instock'+number).val(data[0]);
                    //     $('#rate'+number).val(data[1]);
                    //     var amount=data[0]*data[1];
                    //     $('#net_amount'+number).val(amount);
                    if (data[0]==0)
                    {
                        $("#"+item).css("background-color", "red");
                    }
                    else
                    {
                        $("#"+item).css("background-color", "");
                    }

                }
            });

        }

        $(".amount").keyup(function(){

        });


        function calc(number)
        {

            var qty=parseFloat($('#qty'+number).val());
            if (isNaN(qty))
            {
                qty=0;
            }
            var rate=parseFloat($('#amount'+number).val());

            if (isNaN(rate))
            {
                rate=0;
            }
            var total=qty*rate;
            $('#net_amount'+number).val(total);

            var total=0;
            $(".amount").each(function(){

                total+=+  $(this).val();
            });
            $('#total').val(total);
        }
    </script>


    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection