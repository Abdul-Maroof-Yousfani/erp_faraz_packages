<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client')
{
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;


$pos_no=SalesHelper::uniqe_no_for_pos(date('y'),date('m'));

?>
@extends('layouts.default')

@section('content')

    @include('select2')
    @include('number_formate')
    @include('bundles_data')
    @include('modal')

    <style>
        * {
            font-size: 12px!important;

        }
        label {
            text-transform: capitalize;
        }
    </style>
    <?php  ?>

    <div class="container-fluid">
        <div id="roww" class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">POS </span>
                        </div>
                    </div>

                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'sad/add_pos?m='.$m.'','id'=>'createSalesOrder','class'=>'stop'));?>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label for="">Location</label>
                            <select name="location_id" id="location_id" class="form-control" onchange="get_pos_form()">
                                <option value="">Select Location</option>
                                <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->name;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="row text-center">
                        <h3 style="font-size: large!important;font-weight: bold" id="location"></h3>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                    </div>
                    <div class="row" style="display: none;" id="PosForm">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <input type="hidden" name="location_value" id="location_value" value="">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">




                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">POS NO <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <input readonly type="text" class="form-control" placeholder="" name="pos_no" id="so_no" value="{{strtoupper($pos_no)}}" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">POS Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <input autofocus type="date" class="form-control" placeholder="" name="pos_date" id="so_date" value="{{date('Y-m-d')}}" />
                                                </div>
                                                <input type="hidden" id="sales_order_id" name="sales_order_id" />
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Customer Name <span>  <strong>*</strong></span></label>

                                                    <input  type="text" class="form-control requiredField" placeholder="" name="customer_name" id="customer_name" value="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Customer Contact No<span class="rflabelsteric"></span></label>
                                                    <input  type="text" class="form-control" placeholder="" name="customer_contact_no" id="customer_contact_no" value="" />
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Ref No</label>
                                                    <input  type="text" class="form-control" placeholder="" name="ref_no" id="ref_no" value="" />
                                                </div>

                                            </div>



                                            <div class="row">




                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Description</label>
                                                    <textarea  name="description" id="description" rows="3" cols="50" style="resize:none;text-transform: capitalize" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <input type="hidden" name="demand_type" id="demand_type">
                                            <div class="row">


                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="subHeadingLabelClass">Pos Detail</span>

                                    </div>
                                    <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive" >
                                                <table class="table table-bordered">

                                                    <thead>
                                                    <tr class="text-center">
                                                        <th colspan="6" class="text-center">POS Detail</th>
                                                        <th colspan="2" class="text-center">
                                                            <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                                        </th>
                                                        <th class="text-center">
                                                            <span class="badge badge-success" id="span">1</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 35%;">Item</th>
                                                        <th class="text-center">Bact Code<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center"> QTY.<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Discount %<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Discount Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Net Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Delete<span class="rflabelsteric"><strong>*</strong></span></th>

                                                    </tr>
                                                    </thead>
                                                    <tbody id="AppnedHtml">
                                                    <tr class="cnt" title="1">
                                                        <td>
                                                            <input  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_1" placeholder="ITEM">
                                                            <input type="hidden" class="requiredField" name="item_id[]" id="sub_1">
                                                            <input type="hidden" class="" name="product_id[]" id="product_1">
                                                        </td>
                                                        <td>
                                                            <select onchange="get_stock_qty(this.id,'{{1}}');" name="batch_code[]" id="batch_code1" class="form-control requiredField">

                                                            </select>
                                                            <input readonly type="hidden" class="form-control" name="uom_id[]" id="uom_id1" >
                                                        </td>

                                                        <td> <input class="form-control" type="number" readonly id="instock_1"/> </td>
                                                        <td>
                                                            <input type="text" onkeyup="claculation('1')" onblur="claculation('1')" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty1" placeholder="ACTUAL QTY" min="1" value="0.00">
                                                        </td>

                                                        <td>
                                                            <input type="text" onkeyup="claculation('1')" onblur="claculation('1')" class="form-control requiredField" name="rate[]" id="rate1" placeholder="RATE" min="1" value="0.00">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="amount[]" id="amount1" placeholder="AMOUNT" min="1" value="0.00" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="discount_percent(this.id)" onblur="discount_percent(this.id)" class="form-control requiredField" name="discount_percent[]" id="discount_percent1" placeholder="DISCOUNT" min="1" value="0.00">
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="discount_amount(this.id)" onblur="discount_amount(this.id)" class="form-control requiredField" name="discount_amount[]" id="discount_amount1" placeholder="DISCOUNT" min="1" value="0.00">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount1" placeholder="NET AMOUNT" min="1" value="0.00" readonly>
                                                        </td>
                                                        <td style="background-color: #ccc"></td>

                                                    </tr>
                                                    </tbody>
                                                    <tbody>
                                                    <tr  style="font-size:large;font-weight: bold">
                                                        <td class="text-center" colspan="7">Total</td>
                                                        <td id="" class="text-right" colspan="1"><input readonly class="form-control" type="text" id="net"/> </td>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <span class="subHeadingLabelClass">Addional Expenses</span>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list">
                                                    <thead>
                                                    <th class="text-center">Account Head</th>
                                                    <th class="text-center">Expense Amount</th>
                                                    <th class="text-center">
                                                        <button type="button" class="btn btn-xs btn-primary" id="BtnAddMoreExpense" onclick="AddMoreExpense()">More Expense</button>
                                                    </th>
                                                    </thead>
                                                    <tbody id="AppendExpense">

                                                    </tbody>

                                                    <tr>
                                                        <td colspan="1">Total</td>
                                                        <td colspan="1"><input id="exp_total" type="text" readonly class="exp_total form-control"/> </td>
                                                    </tr>


                                                </table>
                                            </div>
                                        </div>


                                    </div>

                                    <div  class="form-group form-inline text-right">
                                        <label for="email">Grand Total </label>
                                        <input style="font-size: medium!important;font-weight: bold" readonly type="text" class="form-control" id="total">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="demandsSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success hidee']) }}

                                <img  style="display: none!important;" class="center showww" src="{{url("/storage/app/uploads/Loading-bar.gif")}}">


                            </div>

                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>    
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <script>

        function get_pos_form()
        {
            var LocationId = $('#location_id').val();
            if(LocationId !="")
            {
                $('#PosForm').fadeIn('slow');
                //$('.select2 .select2-container').css('width','100% !important');
                $("#ntn").select2({ width: 'resolve' });
                $('#location_value').val(LocationId);
                $("#location_id").css("display", "none");
                var text=  $("#location_id option:selected").text();
                $('#location').html(text);

            }
        }

        var Counter = 1;
        function AddMoreDetails()
        {
            Counter++;
            $('#AppnedHtml').append('<tr class="cnt" id="RemoveRows'+Counter+'">' +
                    '<td class="AutoCounter" title="'+AutoCount+'">' +
                    '<input  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_'+Counter+'" placeholder="ITEM">' +
                    '<input type="hidden" class="" name="item_id[]" id="sub_'+Counter+'"><input type="hidden" class="" name="product_id[]" id="product_'+Counter+'">'+
                    '</td>' +
                    '<td>' +
                    '<select onchange="get_stock_qty(this.id,'+Counter+')" name="batch_code[]" id="batch_code'+Counter+'" class="form-control requiredField">' +
                    '<option value="">Select Batch Code</option>'+
                    <?php foreach($BatchCode as $BFil):?>
                    '<option value="<?php echo $BFil->batch_code?>"><?php echo $BFil->batch_code?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '<input readonly type="hidden" class="form-control" name="uom_id[]" id="uom_id'+Counter+'" >' +
                    '</td>' +
                    '<td> <input class="form-control" type="number" readonly id="instock_'+Counter+'"/> </td>'+
                    '<td>' +
                    '<input type="text" onkeyup="claculation('+Counter+')" onblur="claculation('+Counter+')" class="form-control zerovalidate" name="actual_qty[]" id="actual_qty'+Counter+'" placeholder="ACTUAL QTY">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" onkeyup="claculation('+Counter+')" onblur="claculation('+Counter+')" class="form-control" name="rate[]" id="rate'+Counter+'" placeholder="RATE">' +
                    '</td>' +
                    '<td>' +
                    '<input readonly type="text" class="form-control" name="amount[]" id="amount'+Counter+'" placeholder="AMOUNT">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" onkeyup="discount_percent(this.id)" onblur="discount_percent(this.id)" class="form-control" value="0" name="discount_percent[]" id="discount_percent'+Counter+'" placeholder="DISCOUNT">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" onkeyup="discount_amount(this.id)" onblur="discount_amount(this.id)" class="form-control" value="0" name="discount_amount[]" id="discount_amount'+Counter+'" placeholder="DISCOUNT">' +
                    '</td>' +
                    '<td>' +
                    '<input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount'+Counter+'" placeholder="NET AMOUNT">' +
                    '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')"> - </button>' +
                    '</td>' +
                    '</tr>');
            $('.select2').select2();

            var AutoCount=1;;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).prop('title', AutoCount);

            });

            $('.sam_jass').bind("enterKey",function(e)
            {
                var check =(this.id).split('_');

                if ($('#product_'+check[1]).val()!='')
                {
                    alert('Bundles Selectd Against This');
                    return false;
                }
                $('#items').modal('show');
            });

            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKey");
                }
            });

            $('.sam_jass').bind("enterKeyy",function(e)
            {
                $('#budles_dataa').modal('show');
            });

            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 113)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKeyy");
                }
            });


            $('.sami').bind("enterKey",function(e){
                $('#items_searc_for_bundless').modal('show');
            });

            $('.sami').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_idd=this.id;
                    $(this).trigger("enterKey");
                }
            });

            var itemsCount = $(".cnt").length;
            $('#span').text(itemsCount);
        }



        var CounterExpense = 1;
        function AddMoreExpense()
        {
            CounterExpense++;
            $('#AppendExpense').append("<tr id='RemoveExpenseRow"+CounterExpense+"'>" +
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+CounterExpense+"'><option value=''>Select Account</option><?php foreach(CommonHelper::get_all_account() as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "</td>" +
                    "<td>" +
                    "<input onkeyup='exp_amount_cal()' type='number' name='expense_amount[]' id='expense_amount"+CounterExpense+"' class='form-control requiredField exp_amount'>" +
                    "</td>" +
                    "<td class='text-center'>" +
                    "<button type='button' id='BtnRemoveExpense"+CounterExpense+"' class='btn btn-sm btn-danger' onclick='RemoveExpense("+CounterExpense+")'> - </button>" +
                    "</td>" +
                    "</tr>");
            $('#account_id'+CounterExpense).select2();
        }


        function RemoveSection(Row) {
//            alert(Row);
            $('#RemoveRows' + Row).remove();
            //   $(".AutoCounter").html('');
            var AutoCount = 1;
            var AutoCount=1;;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).prop('title', AutoCount);
            });
            var itemsCount = $(".cnt").length;
            $('#span').text(itemsCount);
        }

        var x=0;


        $('.sam_jass').bind("enterKey",function(e){
            var check =(this.id).split('_');
            if ($('#product_'+check[1]).val()!='')
            {
                alert('Bundles Selectd Against This');
                return false;
            }
            $('#items').modal('show');
        });

        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");
            }
        });

        $('.sam_jass').bind("enterKeyy",function(e){
            $('#budles_dataa').modal('show');
        });

        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 113)
            {
                selected_id=this.id;
                $(this).trigger("enterKeyy");
            }
        });

        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13)
            {
                e.preventDefault();
                return false;
            }
        });

        function net_amount()
        {
            var amount=0;
            $('.net_amount_dis').each(function (i, obj) {
                amount += +$('#'+obj.id).val();
            });


            var exp_amount=0;
            $('.exp_amount').each(function (i, obj) {
                exp_amount += +$('#'+obj.id).val();
            });

            $('#exp_total').val(exp_amount);



            if (isNaN(exp_amount))
            {
                net=0;
            }
            exp_amount=parseFloat(exp_amount);
            $('#total').val(amount+exp_amount);

            amount=parseFloat(amount);
            $('#net').val(amount);
            var sales_tax  = parseFloat($('#sales_amount_td').val());
            $('#net_after_tax').val(amount+sales_tax);
            $('#d_t_amount_1').val(amount+sales_tax);
        }

        function exp_amount_cal()
        {
            var exp_amount=0;
            $('.exp_amount').each(function (i, obj) {
                exp_amount += +$('#'+obj.id).val();
            });

            $('#exp_total').val(exp_amount);

            var net=parseFloat($('#net').val());
            if (isNaN(net))
            {
                net=0;
            }
            exp_amount=parseFloat(exp_amount);
            $('#total').val(net+exp_amount);
        }
        $(document).ready(function() {

            $('#exp_total').number(true,2);
            $('#total').number(true,2);
            $(".btn-success").click(function(e)
            {
                var purchaseRequest = new Array();
                var val;

                purchaseRequest.push($(this).val());

                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                jqueryValidationCustom();
                if(validate == 0)
                {
                    //alert(response);
                }else
                {
                    return false;
                }
            }
            });
        });

        function removeSeletedPurchaseRequestRows(id,counter){
            var totalCounter = $('#totalCounter').val();
            if(totalCounter == 1){
                alert('Last Row Not Deleted');
            }else{
                var lessCounter = totalCounter - 1;
                var totalCounter = $('#totalCounter').val(lessCounter);
                var elem = document.getElementById('removeSelectedPurchaseRequestRow_'+counter+'');
                elem.parentNode.removeChild(elem);
            }
        }

        function claculation(number)
        {
            var  qty=parseFloat($('#actual_qty'+number).val());
            var instock=parseFloat($('#instock_'+number).val());


            if (qty>instock)
            {
                alert('Can not Exceed '+instock);
                $('#actual_qty'+number).val(0);
                qty=0;
            }



            var  rate=$('#rate'+number).val();
            var total=parseFloat(qty*rate).toFixed(2);
            $('#amount'+number).val(total);
            var amount = 0;
            count=1;
            $('.net_amount_dis').each(function (i, obj) {

                amount += +$('#'+obj.id).val();

                count++;
            });

            amount=parseFloat(amount);

            discount_percent('discount_percent'+number);
            net_amount();
            //      sales_tax();
        }

        function discount_percent(id)
        {
            var  number= id.replace("discount_percent","");
            var amount = $('#amount' + number).val();
            var x = parseFloat($('#'+id).val());

            if (x >100)
            {
                alert('Percentage Cannot Exceed by 100');
                $('#'+id).val(0);
                x=0;
            }

            x=x*amount;
            var discount_amount =parseFloat( x / 100).toFixed(2);
            $('#discount_amount'+number).val(discount_amount);
            var discount_amount=$('#discount_amount'+number).val();

            if (isNaN(discount_amount))
            {
                $('#discount_amount'+number).val(0);
                discount_amount=0;
            }
            var amount_after_discount=amount-discount_amount;

            $('#after_dis_amount'+number).val(amount_after_discount);
            var amount_after_discount=$('#after_dis_amount'+number).val();

            if (amount_after_discount==0)
            {
                $('#after_dis_amount'+number).val(amount);
                $('#net_amounttd_'+number).val(amount);
                $('#net_amount'+number).val(amount_after_discount);
            }

            else
            {
                $('#net_amounttd_'+number).val(amount_after_discount);
                $('#after_dis_amount'+number).val(amount_after_discount);
            }

            $('#cost_center_dept_amount'+number).text(amount_after_discount);
            $('#cost_center_dept_hidden_amount'+number).val(amount_after_discount);

            //     sales_tax('sales_taxx');
            net_amount();
            //      sales_tax();
        }


        function discount_amount(id)
        {
            var  number= id.replace("discount_amount","");
            var amount=parseFloat($('#amount'+number).val());

            var discount_amount=parseFloat($('#'+id).val());

            if (discount_amount > amount)
            {
                alert('Amount Cannot Exceed by '+amount);
                $('#discount_amount'+number).val(0);
                discount_amount=0;
            }

            if (isNaN(discount_amount))
            {
                $('#discount_amount'+number).val(0);
                discount_amount=0;
            }

            var percent=(discount_amount / amount *100).toFixed(2);
            $('#discount_percent'+number).val(percent);
            var amount_after_discount=amount-discount_amount;
            $('#after_dis_amount'+number).val(amount_after_discount);
            $('#net_amounttd_'+number).val(amount_after_discount);
            $('#net_amount_'+number).val(amount_after_discount);
            //  sales_tax('sales_taxx');

            net_amount();
            //     sales_tax();
        }


        function get_detail(id,number)
        {
            //alert(number); return false;
            var item=$('#'+id).val();
            var location_id = $('#location_value').val();
            $.ajax({
                url:'{{url('/pdc/get_batch_code')}}',
                data:{item:item,location_id:location_id},
                type:'GET',
                success:function(response)
                {
                    //var data=response.split(',');
                    $('#batch_code'+number).html(response);
                }
            });
        }

        function get_detail(id,number)
        {
            //alert(number); return false;
            var item=$('#'+id).val();
            var location_id = $('#location_value').val();
            $.ajax({
                url:'{{url('/pdc/get_batch_code')}}',
                data:{item:item,location_id:location_id},
                type:'GET',
                success:function(response)
                {
                    //var data=response.split(',');
                    $('#batch_code'+number).html(response);
                }
            });
        }

        $(".remove").each(function(){

            $(this).html($(this).html().replace(/,/g , ''));
        });
        function get_ntn()
        {
            var ntn=$('#ntn').val();
            ntn=ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
            $('#buyers_sales').val(ntn[2]);
            $('#model_terms_of_payment').val(ntn[3]);
            calculate_due_date();
            sales_tax();
        }

        function calculate_due_date()
        {

            var days=parseFloat($('#model_terms_of_payment').val())-1;
            var tt = document.getElementById('so_date').value;

            var date = new Date(tt);
            var newdate = new Date(date);
            newdate.setDate(newdate.getDate() + days);
            var dd = newdate.getDate();

            var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
            var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
            var y = newdate.getFullYear();
            var someFormattedDate =  + y+'-'+ mm +'-'+dd;

            document.getElementById('due_date').value = someFormattedDate;
        }
        function sales_tax()
        {

            var total=	parseFloat($('#net').val());
            if (isNaN(total))
            {
                total=0;
            }

            if($("#sales_tax_applicable").prop('checked') == false)
            {
                total=0;
            }

            var sales_tax_percent=parseFloat($('#sales_percent').val());
            var sales_tax=((total/100)*sales_tax_percent).toFixed(2);
            $('#sales_tax').val(sales_tax);


            var strn= $('#buyers_sales').val();
            var total=	parseFloat($('#net').val());

            if($("#sales_tax_further_applicable").prop('checked') == false)
            {
                total=0;
            }

            if (strn=='')
            {
                var sales_tax_percent=parseFloat($('#sales_percent_other').val());
                var sales_tax_further=((total/100)*sales_tax_percent).toFixed(2);
                $('#sales_tax_further').val(sales_tax_further);

            }
            else
            {
                sales_tax_further=0;
                $('#sales_tax_further').val(0);
            }

            total_cal();


            toWords(1);
        }


        function total_cal()
        {
            var sales_tax_amount=parseFloat($('#sales_tax').val());
            var sales_tax_amount_further=parseFloat($('#sales_tax_further').val());
            var total=sales_tax_amount+sales_tax_amount_further;
            $('#sales_total').val(total);

            var before_tax=parseFloat($('#net').val());


            $('#total').val(before_tax);
            var after_tax=parseFloat($('#sales_total').val());
            var total_after=before_tax+after_tax;
            $('#total_after_sales_tax').val(total_after);

            $('#d_t_amount_1').val(total_after);
        }

        function applicable()
        {
            sales_tax();
        }


        function get_stock_qty(warehouse,number)
        {


            var warehouse=$('#location_id').val();
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
                    $('#instock_'+number).val(data[0]);
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


        $("#createSalesOrder").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');

            $('.hidee').prop('disabled', true);
            $.ajax({
                type: "GET",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(data)
                {
                    $(".hidee").fadeIn(500);
                    $(".showww").fadeOut(500);
                    $('#showDetailModelOneParamerter').modal('show');
                    $('.modal-body').html(data); // show response from the php script.


                },
                error: function(data, errorThrown)
                {
                    $('.hidee').prop('disabled', false);
                    alert('request failed :'+errorThrown);
                }
            });


        });

        $('#showDetailModelOneParamerter').on('hidden.bs.modal', function () {
            alert();
            $('.hidee').prop('disabled', false);
        })
        
    </script>
    <script type="text/javascript">

        $('.select2').select2();
    </script>


@endsection