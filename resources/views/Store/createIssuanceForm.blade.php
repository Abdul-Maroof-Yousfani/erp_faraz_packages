<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
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

    <?php
    $wo=StoreHelper::unique_for_wo(date('y'),date('m'));
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                @include('Purchase.'.$accType.'purchaseMenu')
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Work Order Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'stad/addIssuanceDetail?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
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
                                                    <input type="text" readonly class="form-control" value="<?php echo $wo?>">
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label for="">Voucher Date</label>
                                                    <input type="date" class="form-control" id="voucher_date" name="voucher_date" value="<?php echo date('Y-m-d')?>">
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label for="">Supplier</label>
                                                    <select name="supplier_id" id="supplier_id" class="form-control requiredField">
                                                        <option value="">Select Supplier</option>
                                                        <?php foreach(CommonHelper::get_all_supplier() as $Fil): ?>
                                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>


                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="sf-label">Description</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <textarea name="description_1" id="description_1" rows="2" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
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

                                                <table style="" class="table table-bordered well">
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
                                                    <tr id="" class="AutoNo">
                                                        <td>
                                                            <input ondblclick="clear_fiel(this.id)"  onchange="get_detail(this.id,1)" type="text" class="form-control sam_jass" name="sub_ic_des[]" id="item_1">
                                                            <input type="hidden" class="requiredField" name="item_id[]" id="sub_1">
                                                        </td>


                                                        <td>
                                                            <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id1">
                                                        </td>
                                                        <td>
                                                            <input onblur="calc('{{1}}')" onkeyup="calc('{{1}}')"  type="text" class="form-control requiredField" name="qty[]" id="qty1">
                                                        </td>

                                                        <td>
                                                            <select name="maketype[]" id="maketype1" class="form-control requiredField">
                                                                <option value="">Select</option>
                                                                <option value="1">Cutting</option>
                                                                <option value="2">Welding</option>
                                                                <option value="3">Machining</option>
                                                                <option value="4">Thread Making</option>
                                                                <option value="5">Jointing</option>
                                                                <option value="6">APS</option>
                                                                <option value="7">Galvanise</option>
                                                                <option value="8">Service & Marking</option>
                                                                <option value="9">Service</option>
                                                                <option value="10">Marking</option>
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input onblur="calc('{{1}}')" onkeyup="calc('{{1}}')" type="text" class="form-control  requiredField" name="amount[]" id="amount1">
                                                        </td>

                                                        <td>
                                                            <input readonly type="text" class="form-control amount requiredField" name="net_amount[]" id="net_amount1">
                                                        </td>



                                                        <td class="text-center">- - -</td>
                                                    </tr>
                                                    </tbody>
                                                    <tr>
                                                        <td colspan="5">Total</td>
                                                        <td style="font-weight: bold;font-size: large!important;" colspan="1">  <input readonly type="text" class="form-control requiredField" name="" id="total"></td>
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
        </div>
    </div>    

    <script>
        $(document).ready(function(){
            $('#supplier_id').select2();
            $('#total').number(true,2);
        });
        var Counter = 1;


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