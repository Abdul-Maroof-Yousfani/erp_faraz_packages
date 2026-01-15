<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;

use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
?>


@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

<style>
    .select2-container {
    font-size: 11px;
    }
</style>




    <?php $sales_tax_count=1;?>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
            <!--
       / include('Purchase.'.$accType.'purchaseMenu')
                    <!-->
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Purchase Voucher Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'pad/editPurchaseVoucher/'.$id,'id'=>'cashPaymentVoucherForm','enctype'=>'multipart/form-data'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!--
                    <input type="hidden" name="pageType" value="< ?php echo $_GET['pageType']?>">

                    <input type="hidden" name="parentCode" value="< ?php echo $_GET['parentCode']?>">   <!-->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php  //$str = DB::connection('mysql2')->selectOne("select count(id)id from purchase_voucher where status=1 and purchase_date='".date('Y-m-d')."'")->id;
                                          //  $pv_no = 'pv'.($str+1); ?>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Pv No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                   <input readonly autofocus type="text" class="form-control requiredField"  placeholder="" name="pv_no" id="pv_no" value="{{$purchase_voucher->pv_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Pv Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input onblur="change_day()" onchange="change_day()"
                                                       type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="purchase_date" id="demand_date_1" value="<?php echo $purchase_voucher->pv_date ?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Pv Day.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Ref / Bill No"
                                                       name="slip_no" id="slip_no_1" value="{{$purchase_voucher->slip_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Bill Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField"  name="bill_date" id="bill_date" value="{{$purchase_voucher->purchase_date}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input value="{{$purchase_voucher->due_date}}" type="date" name="due_date" id="due_date" class="form-control"/>
                                            </div>





                                        </div>
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>         <div class="lineHeight">&nbsp;</div>         <div class="lineHeight">&nbsp;</div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"><a tabindex="-1" href="#" onclick="showDetailModelOneParamerter('pdc/createPurchaseTypeForm')" class="">Purchase Type</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select  class="form-control  select2" name="p_type" id="p_type">
                                                    <option value="">Select Demand Type</option>
                                                    @foreach(CommonHelper::get_all_purchase_type() as $row)
                                                        <option @if($purchase_voucher->purchase_type==$row->id) selected @endif value="{{$row->id}}">{{ucwords($row->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax')" class="">Vendor</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_current_amount(this.id)" name="supplier" id="supplier" class="form-control select2 requiredField">
                                                    <option value=""> SELECT</option>

                                                    @foreach($supplier as $row)
                                                        <option @if($purchase_voucher->supplier==$row->id) selected @endif value="{{$row->id}}">{{ucwords($row->name)}}</option>
                                                    @endforeach;

                                                </select>

                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Vendor Current Amount <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly  type="number" class="form-control" placeholder="" name="current_amount" id="current_amount" value="" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select name="curren" id="curren" class="form-control select2 requiredField">
                                                    <option value="0,1"> Pkr</option>

                                                    @foreach(CommonHelper::get_all_currency() as $row)
                                                        <option @if($purchase_voucher->currency==$row->id) selected @endif  value="{{$row->id.','.$row->rate}}">{{$row->curreny.'------'.$row->rate}}</option>
                                                    @endforeach;

                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="description" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField">{{$purchase_voucher->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div  class="table-responsive">
                                            <div  class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
<?php $count1=1;

                                                ?>
                                                @foreach($purchase_voucher_data as $purchase_data)
                                                <table  id="removeDemandsRows_1_<?php echo $count1?>" class="table table-bordered">
                                                    <thead>
                                                    <tr>

                                                        <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax/category_id_1_1')" class="">Expense</a>
                                                            <strong>*</strong></span>
                                                        </th>
                                                        <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
                                                        <th style="width: 100px" class="text-center">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Sales Tax Acc</a>
                                                        <th style="width: 150px;" class="text-center" style="">Sales Tax Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center" style="">Total Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <!--
                                                        <th style="width: 200px;" class="text-center" style="">Department <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <!-->



                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                                                    <tr>
                                                        <td>

                                                            <!--
                                                                <select style="width: 100%" name="category_id_1_1" id="category_id_1_1" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField select2">
                                                                    < ?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                                                    </select>
                                                                                            -->
                                                            <select  style="width: 200px;font-size: 10px;" class="form-control requiredField select2"  id="category_id_1_<?php echo $count1 ?>" class="form-control requiredField" name="category_id_1_<?php echo $count1 ?>" onchange="">
                                                                <option value="">Select Expense</option>

                                                                <?php

                                                                ?>
                                                                @foreach(FinanceHelper::get_accounts() as $row)
                                                                    <option @if($purchase_data->category_id==$row->id) selected @endif value="{{ $row->id}}">{{ ucwords($row->name)}}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_<?php echo $count1 ?>" id="sub_item_id_1_<?php echo $count1 ?>" class="form-control select2">
                                                                <option value="">Select</option>

                                                                @foreach(CommonHelper::get_all_subitem() as $row)
                                                                    <option @if($purchase_data->sub_item==$row->id) selected @endif value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input value="{{CommonHelper::get_uom_name($purchase_data->uom)}}" type="text" name="uom_1_<?php echo $count1 ?>" id="uom_1_<?php echo $count1 ?>" class="form-control" />
                                                            <input value="{{$purchase_data->uom}}" type="hidden" name="uom_id_1_<?php echo $count1 ?>" id="uom_id_1_<?php echo $count1 ?>" class="form-control" />
                                                        </td>

                                                        <td>
                                                            <input value="{{$purchase_data->qty}}" onkeyup="calculation_amount(this.id)"  type="number" step="0.01" name="qty_1_<?php echo $count1 ?>" id="qty_1_<?php echo $count1 ?>" class="form-control qty" />
                                                        </td>

                                                        <td>
                                                            <input value="{{$purchase_data->rate}}" onkeyup="calculation_amount(this.id)" type="number" step="0.01" name="rate_1_<?php echo $count1 ?>" id="rate_1_<?php echo $count1 ?>" class="form-control rate" />
                                                        </td>

                                                        <td>
                                                          <input value="{{$purchase_data->amount}}" onkeyup="pick_amount(this.id,'amount_1_<?php echo $count1 ?>');calc_amount(this.id)" type="text"  name="amounttd_1_<?php echo $count1 ?>" id="amounttd_1_<?php echo $count1 ?>" class="form-control requiredField amount" />
                                                            <input type="hidden" step="0.01" name="amount_1_<?php echo $count1 ?>" id="amount_1_<?php echo $count1 ?>" value="{{$purchase_data->amount}}"/>
                                                        </td>


                                                        <td>
                                                            <select   onchange="sales_tax(this.id)" style="width: 200px;" name="accounts_1_<?php echo $count1 ?>" id="accounts_1_<?php echo $count1 ?>"
                                                                      class="form-control sales_tax select2">
                                                                <option value="0">Select</option>
                                                                @foreach(FinanceHelper::get_accounts() as $row)
                                                                    <?php $code=explode('-',$row->code); ?>
                                                                    <option @if($purchase_data->sales_tax_per==$row->id) selected @endif value="{{$row->id}}">{{$code[0].'--'.ucwords($row->name)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <!--
                                                                                                                        <td>
                                                                                                                            <input onkeyup="sales_tax(this.id)" type="number" name="sales_tax_per_1_1" id="sales_tax_per_1_1" class="form-control requiredField sales_tax_per" />
                                                                                                                        </td>
                                                                                                                        <!--->
                                                        <td>
                                                            <input  value="{{$purchase_data->sales_tax_amount}}" onkeyup="pick_amount(this.id,'sales_tax_amount_1_<?php echo $count1 ?>');tax_by_amount(this.id)"    type="text" name="sales_tax_amounttd_1_<?php echo $count1 ?>" id="sales_tax_amounttd_1_<?php echo $count1 ?>" class="form-control requiredField sales_tax_amount" />
                                                            <input value="{{$purchase_data->sales_tax_amount}}" type="hidden" name="sales_tax_amount_1_<?php echo $count1 ?>" id="sales_tax_amount_1_<?php echo $count1 ?>" />
                                                        </td>
                                                        <td>
                                                            <input value="{{$purchase_data->net_amount}}" readonly type="text" name="net_amounttd_1_<?php echo $count1 ?>" id="net_amounttd_1_<?php echo $count1 ?>" class="form-control requiredField" />
                                                            <input value="{{$purchase_data->net_amount}}" class="net_amount" type="hidden" name="net_amount_1_<?php echo $count1 ?>" id="net_amount_1_<?php echo $count1 ?>" />

                                                        </td>

                                                        <!-->

                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <select class="form-control" id="txt_nature_1_<?php echo $count1 ?>" name="txt_nature_1_1">
                                                                <option value="0">Select</option>
                                                                <option @if($purchase_data->txt_nature==1) selected @endif value="1">FBR</option>
                                                                <option @if($purchase_data->txt_nature==2) selected @endif value="2">SRB</option>
                                                                <option @if($purchase_data->txt_nature==3) selected @endif value="3">PRA</option>
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <select class="form-control" name="income_txt_nature_1_<?php echo $count1 ?>" id="income_txt_nature_1_1">
                                                                <option value="0">Select</option>
                                                                <option @if($purchase_data->income_txt_nature==1) selected @endif  value="1">Supplies</option>
                                                                <option @if($purchase_data->income_txt_nature==2) selected @endif value="2">Services</option>

                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>


                                                </table>

                                                <div class="row">


                                                    @include('Purchase.dept_allocation_edit')



                                                    @include('Purchase.cost_center_allocation_edit')



                                                    @include('Purchase.sales_tax_allocation_edit')



                                                </div>

                                                    <?php $count1++; ?>


                                                @endforeach
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <script>
                                    function pick_amount(id,send)
                                    {


                                        var current_amount=$('#'+id).val();
                                        $('#'+send).val(current_amount);
                                    }
                                </script>
                                <!-- for  dept allocation><!-->



                                <!-- for  dept allocation End><!-->



                                <table class="table table-bordered">
                                    <tr>

                                        <td class="col-sm-2" class="text-center" colspan="3">TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td  class="col-sm-2"  colspan="1"><!--<input type="number" maxlength="15" class="form-control text-right" name="total_qty" value="" id="total_qty" readonly="readonly">!--></td>
                                        <td  class="col-sm-2"  colspan="1"><!--<<input  type="number" maxlength="15" class="form-control text-right" name="total_rate" value="" id="total_rate" readonly=""><!--></td>
                                        <td  class="col-sm-2"  colspan="1"><!--<input type="number" maxlength="15" class="form-control text-right" name="total_amount" value="" id="total_amount" readonly="">!--></td>
                                        <td  class="col-sm-2"  class="col-sm-6" colspan="1"><!--<input type="number" maxlength="15" class="form-control text-right" name="total_salesTax_amount" value="" id="total_sales_tax_amount" readonly="">!--> </td>
                                        <td  class="col-sm-2"   colspan="1"><input value="{{$purchase_voucher->total_net_amount}}" tabindex="-1" type="text" maxlength="15" class="form-control text-right" name="total_net_amounttd" value="" id="net_amounttd" readonly=""></td>
                                        <input  type="hidden" name="total_net_amount" id="net_amount" value="{{$purchase_voucher->total_net_amount}}"/>
                                        <input type="hidden" name="d_t_amount_1" id="d_t_amount_1" value=""/>
                                        <td class="text-center" colspan="1"></td>
                                    </tr>
                                </table>

                                <table>
                                    <tr>

                                        <td id="rupees">{{$purchase_voucher->amount_in_words}}</td>
                                        <input type="hidden" value="{{$purchase_voucher->amount_in_words}}" name="rupeess" id="rupeess"/>
                                    </tr>
                                </table>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('1')" value="Add More Demand's Rows" />
                                        <input type="button" onclick="removeDemandsRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove">
                                    </div>
                                </div>


                                <!--department-->


                            </div>

                            <!--start-->



                            <!-->



                        </div>
                        <div class="demandsSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <!--
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                                <!-->
                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var SelectVal=[];
            var Selecttxt=[];
            var ajaxformdept=0;

            var SelectValCostCenter=[];
            var SelecttxtCostCenter=[];
            var ajaxformdeptCostCenter=0;
            $('#bank_detail').change(function(){
                if ($(this).is(':checked'))
                {

                    $(".banks").css("display", "block");
                    $(".required").addClass("requiredField");

                    //   $("#pra").addClass("requiredField");
                } else
                {
                    $(".banks").css("display", "none");
                    $(".required").removeClass("requiredField");
                    //  $('#pra').val("");
                    // $("#pra").removeClass("requiredField");
                }
            });

            $(document).ready(function() {
                change_day();

                var form = $('form');
                // let the browser natively reset defaults


                for (i=1; i<=x; i++)
                {
                    $('#sales_tax_amounttd_1_'+i+'').number(true,2);
                    $('#amounttd_1_'+i+'').number(true,2);
                    $('#net_amounttd_1_'+i+'').number(true,2);


                }
                $('#net_amounttd').number(true,2);
                $('#department_amount_1_1').number(true,2);
                $('#total_dept1').number(true,2);
                $('#cost_center_department_amount_1_1').number(true,2);
                $('#cost_center_total_dept1').number(true,2);
                $('#sales_tax_department_amount_1_1').number(true,2);
                $('#sales_tax_total_dept1').number(true,2);

           //     window.scrollBy(0,180);


                var date = $('#demand_date_1').val();

                var d = new Date(date);
                var weekday = new Array(7);
                weekday[0] = "Sunday";
                weekday[1] = "Monday";
                weekday[2] = "Tuesday";
                weekday[3] = "Wednesday";
                weekday[4] = "Thursday";
                weekday[5] = "Friday";
                weekday[6] = "Saturday";

                var n = weekday[d.getDay()];

            document.getElementById("pv_day").value = n;

                var d = 1;
                $('.addMoreDemands').click(function (e){
                    e.preventDefault();
                    d++;
                    var m = '1';
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
                    $("input[name='demandsSection[]']").each(function(){
                        demands.push($(this).val());
                    });
                    var _token = $("input[name='_token']").val();
                    var auth=dept_amount_validation();
                    var auth1= sales_tax_amount_validation();
                    var auth2=cost_center_amount_validation();
                    for (val of demands) {


                        if (auth==1 && auth1==1 && auth2==1)
                        {
                            jqueryValidationCustom();
                        }
                        else

                        {

                            return false;
                        }
                        if(validate == 0){
                            //alert(response);
                        }else{
                            return false;
                        }
                    }

                });
            });
            var x = '<?php echo $count1-1 ?>';
            function addMoreDemandsDetailRows(id){

                var auth=dept_amount_validation();
                var auth1= sales_tax_amount_validation();
                var auth2=cost_center_amount_validation();




                if (auth == 1 && auth1 == 1 && auth2 == 1) {

                    x++;
                    //alert(id+' ---- '+x);
                    var m = '1';
                    $.ajax({
                        url: '<?php echo url('/')?>/pmfal/addMorPurchaseVoucherRow',
                        type: "GET",
                        data: {counter: x, id: id, m: m},
                        success: function (data) {

                            data = data.split('+');


                            $('.addMoreDemandsDetailRows_' + id + '').append(data[0]);
                            //    $('.dept_part').append(data[1]);
                            //   $('.sales_tax_dept_part').append(data[2]);
                            //   $('.cost_center').append(data[3]);
                            $('#category_id_1_' + x + '').select2();
                            $('#sub_item_id_1_' + x + '').select2();

                            $('#department_1_' + x + '').select2();
                            $('#accounts_1_' + x + '').select2();
                            $('#category_id_1_' + x + '').focus();
                            $('#department_' + x + '_' + 1).select2();
                            $('#cost_center_department_' + x + '_' + 1).select2();
                            $('#sales_tax_department_' + x + '_' + 1).select2();

                            $('#amounttd_'+id+'_'+x+'').number(true,2);
                            $('#sales_tax_amounttd_'+id+'_'+x+'').number(true,2);
                            $('#net_amounttd_'+id+'_'+x+'').number(true,2);

                            $('#department_amount_'+x+'_1').number(true,2);
                            $('#total_dept'+x+'').number(true,2);


                            $('#cost_center_department_amount_'+x+'_1').number(true,2);
                            $('#cost_center_total_dept'+x+'').number(true,2);

                            $('#sales_tax_department_amount_'+x+'_1').number(true,2);
                            $('#sales_tax_total_dept'+x+'').number(true,2);

                            var idd=1;
                     //       window.scrollBy(0,180);
                        }
                    });
                }
            }

            function removeDemandsRows(){

                var id=1;

                if (x > 1)
                {
                    //  var elem = document.getElementById('removeDemandsRows_'+id+'_'+x+'');
                    //   elem.parentNode.removeChild(elem);

                    $('#removeDemandsRows_'+id+'_'+x+'').remove();

                    $('.removeDemandsRows_dept_'+id+'_'+x+'').remove();

                    x--;
                    net_amount_func();

                }


            }
            function removeDemandsSection(id){
                var elem = document.getElementById('Demands_'+id+'');
                elem.parentNode.removeChild(elem);
            }

            function subItemListLoadDepandentCategoryId(id,value) {

                //alert(id+' --- '+value);
                var arr = id.split('_');
                var m = '1';
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                    type: "GET",
                    data: { id:id,m:m,value:value},
                    success:function(data) {

                        $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                    }
                });
            }

            function get_detail_purchase_voucher(id) {



                var number=id.replace("sub_item_id_", "");
                number=number.split('_');
                number=number[1];

                // for finance department
                var dept_name = $('#' + id + ' :selected').text();
                $('#dept_item'+number).text(number+'-'+' '+dept_name);
                $('#cost_center_dept_item'+number).text(number+'-'+' '+dept_name);

                // End
                id=$('#'+id).val();
                var m = '1';
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/get_detail_purchase_voucher',
                    type: "GET",
                    data: { id:id},
                    success:function(data) {

                        data=data.split(',');

                        $('#uom_1_'+number).val(data[0]);
                        $('#rate_1_'+number).val(data[1]);
                        $('#uom_id_1_'+number).val(data[2]);
                    }
                });
            }




            function calc_amount(id)
            {

                var  number= id.replace("amounttd_1_","");
                var amount= $('#'+id).val();

              //  amount=amount.replace(/,/g, "");
                amount= parseFloat(amount);
                if (isNaN(amount)==true)
                {
                    amount=0;
                }

                var tax_amount= $('#sales_tax_amount_1_'+number).val();
                tax_amount=parseFloat(tax_amount);
                var qty=$('#'+'qty_1_'+number).val();

                var totalrate=parseFloat(amount / qty);
                var currency=$('#curren').val();
                currency=currency.split(',');
                currency=currency[1];
                totalrate=totalrate / currency;

                $('#rate_1_'+number).val(totalrate.toFixed(2));

                $('#net_amount_1_'+number).val(amount);

                var net_amount=0;
                var gross_rate=0;
                var count=1;
                $('.amount').each(function()
                {
                    amount=$('#amount_1_'+count).val();
                 //   amount=amount.replace(/,/g, "");
                    amount= parseFloat(amount);
                    net_amount+=+amount;
                    count++;
                });

                count=1;

                net_amount=parseFloat(net_amount).toFixed(2);
                $('#total_amount').val(net_amount);

                $('.rate').each(function()
                {
                    gross_rate+=+$('#rate_1_'+count).val();
                    count++;
                });
                gross_rate=parseFloat(gross_rate).toFixed(2);
                $('#total_rate').val(gross_rate);

                var count=1;
                var net_amount=0;
                $('.net_amount').each(function()
                {

                    net_amount+=+$('#net_amount_1_'+count).val();
                    count++;
                });
                net_amount=parseFloat(net_amount).toFixed(2);

                $('#net_amount').val(net_amount);
                $('#net_amounttd').val(net_amount);


                // for net amount td for number seprator 17-nov-2018
                $('#net_amounttd_1_'+number).val(amount);
              //  formate_number('net_amounttd_1_'+number,number,'net_amount_1_'+number);
                // for net amount td for number seprator 17-nov-2018 End


                var total_amount=$('#net_amount').val();





                if (amount>0)
                {
                    if($("#tax"+number).prop('checked') == true)
                    {
                        $("#sales_tax_amount_1_"+number).attr("readonly", false);
                        $("#sales_tax_per_1_"+number).attr("readonly", true);
                    }

                    if($("#tax"+number).prop('checked') == false)
                    {
                        $("#sales_tax_amount_1_"+number).attr("readonly", true);
                        $("#sales_tax_per_1_"+number).attr("readonly", false);
                    }
                }

                $('d_t_amount_1').val(total_amount);

                sales_tax('accounts_1_'+number);

                // for dept amount
                var dept_amount=parseFloat( $('#amount_1_'+number).val()).toFixed(2);

                $('#dept_amount'+number).text(dept_amount);
                $('#dept_hidden_amount'+number).val(dept_amount);
                $('#cost_center_dept_amount'+number).text(dept_amount);
                $('#cost_center_dept_hidden_amount'+number).val(dept_amount);
                // end
                net_amount_func();
                dept_allocation_amount_display(number);
                cost_center_allocation_amount_display(number);
                toWords();


            }

            function calculation_amount(id)
            {

                var   number= id.split('_');
                number=number[2];

                var rate= $('#rate_1_'+number).val();
                if (isNaN(rate)==true)
                {
                    rate=0;
                }
                var qty=$('#'+'qty_1_'+number).val();
                if (isNaN(qty)==true)
                {
                    qty=0;
                }


                var currency=$('#curren').val();
                currency=currency.split(',');
                currency=currency[1];

                rate=currency * rate;
                var total_amount=parseFloat(rate * qty).toFixed(2);
                $('#amount_1_'+number).val(total_amount);

                // for amount td for number seprator  16-nov-2018
                $('#amounttd_1_'+number).val(total_amount);
           //     formate_number('amounttd_1_'+number,number,'amount_1_'+number);
                // for amount td for number seprator End 16-nov-2018 End


                // for net amount td for comma seprated 17-nov-2018 end
                $('#net_amount_1_'+number).val(total_amount);
                $('#net_amounttd_1_'+number).val(total_amount);
             //   formate_number('net_amounttd_1_'+number,number,'net_amount_1_'+number);
                // for net amount td for comma seprated 17-nov-2018 end

                var net_amount=0;
                var gross_rate=0;
                var count=1;
                $('.amount').each(function()
                {

                    net_amount+=+$('#amount_1_'+count).val();
                    count++;
                });

                net_amount=parseFloat(net_amount).toFixed(2);


                count=1
                $('#total_amount').val(net_amount);

                $('.rate').each(function()
                {
                    gross_rate+=+$('#rate_1_'+count).val();
                    count++;
                });

                gross_rate=parseFloat(gross_rate).toFixed(2);
                $('#total_rate').val(gross_rate);

                var count=1;
                var net_amount=0;
                $('.net_amount').each(function()
                {

                    net_amount+=+$('#net_amount_1_'+count).val();
                    count++;
                });


                net_amount=parseFloat(net_amount).toFixed(2);
                //    alert(net_amount);

                //
                $('#net_amount').val(net_amount);



                var count=1;
                var qty=0;
                $('.qty').each(function()
                {

                    qty+=+$('#qty_1_'+count).val();
                    count++;
                });
                $('#total_qty').val(qty);

                var amount=$('#net_amount').val();





                var amount=$('#amount_1_'+number).val();

                var total_amount=$('#net_amount').val();
                $('#d_t_amount_1').val(total_amount);
                toWords(1);

                // for dept amount

                $('#dept_amount'+number).text(amount);
                $('#dept_hidden_amount'+number).val(amount);
                $('#cost_center_dept_amount'+number).text(amount);
                $('#cost_center_dept_hidden_amount'+number).val(amount);
                // end
                net_amount_func();
                sales_tax('accounts_1_'+number);
                dept_allocation_amount_display(number);
                cost_center_allocation_amount_display(number);

            }


            function sales_tax(id) {

                var  number= id.replace("accounts_1_","");
                var sales_tax_per_value = $('#'+id).val();

                // for sales_tax department
                var dept_name = $('#' + id + ' :selected').text();


                $('#sales_tax_dept_item'+number).text(number+'-'+' '+dept_name);
                // End

                if (sales_tax_per_value!=0) {
                    var sales_tax_per = $('#' + id + ' :selected').text();
                    sales_tax_per = sales_tax_per.split('(');
                    sales_tax_per = sales_tax_per[1];
                    sales_tax_per = sales_tax_per.replace('%)', '');
                }
                else
                {
                    sales_tax_per=0;
                }




                if (isNaN(sales_tax_per)==true)
                {
                    sales_tax_per=0;
                }

                if (sales_tax_per > 100) {
                    $('#' + id).val(0);
                    $('#sales_tax_amount_1_' + number).val(0);
                    alert('PERCENTAGE SHULD BE LESS THAN 100');
                    var b = $('#amount_1_' + number).val();

                    $('#net_amount_1_' + number).val(b);

                    //for net amount td for comma seprated 17-nov-2018
                    $('#net_amounttd_1_'+number).val(b);
                  //  formate_number('net_amounttd_1_'+number,number,'net_amount_1_'+number);
                    //for net amount td for comma seprated 17-nov-2018

                    var count = 1;
                    var total_sales_tax_per = 0;
                    $('.sales_tax_per').each(function () {

                        total_sales_tax_per += +$('#sales_tax_per_1_' + count).val();
                        count++;
                    });
                    $('#total_sales_tax_per').val(total_sales_tax_per + '%');



                    var count = 1;
                    var total_sales_tax_amount = 0;
                    $('.sales_tax_amount').each(function () {

                        total_sales_tax_amount += +$('#sales_tax_amount_1_' + count).val();
                        count++;
                    });
                    $('#total_sales_tax_amount').val(total_sales_tax_amount);

                    var count = 1;
                    var net_amount = 0;
                    $('.net_amount').each(function () {


                        net_amount += +$('#net_amount' + count).val();
                        count++;
                    });
                    net_amount=parseFloat(net_amount).toFixed(2);
                    $('#net_amount').val(net_amount);
                    return false;
                }
                var amount = $('#amount_1_' + number).val();
              //  amount=amount.replace(/,/g, "");


                var x = parseFloat(sales_tax_per * amount);



                var s_tax_amount =parseFloat( x / 100).toFixed(2);

                $('#sales_tax_amounttd_1_' + number).val(s_tax_amount);
                $('#sales_tax_amount_1_' + number).val(s_tax_amount);


                // for comma seprated

              //  formate_number('sales_tax_amounttd_1_'+number,number,'sales_tax_amount_1_'+number)
                // end comma seprated

                // for sales_tax department amount
                var dept_name = $('#' + id + ' :selected').text();
                var sales_tax_amount=$('#sales_tax_amount_1_'+number).val();
                $('#sales_tax_dept_amount'+number).text(sales_tax_amount);
                $('#sales_tax_dept_hidden_amount'+number).val(sales_tax_amount);
                // End

                var amount = $('#amount_1_' + number).val();
             //   amount=amount.replace(/,/g, "");
                amount=parseFloat(amount);
                var net_amount = parseFloat($('#sales_tax_amount_1_' + number).val());


                var all_net_amount = parseFloat(amount + net_amount).toFixed(2);

                $('#net_amount_1_' + number).val(all_net_amount);


                // for td net amount for comma seprate 17-nov-2018

                $('#net_amounttd_1_'+number).val(all_net_amount);
           //     formate_number('net_amounttd_1_'+number,number,'net_amount_1_'+number);

                // for td net amount for comma seprate 17-nov-2018 End

                var count = 1;
                var total_sales_tax_per = 0;



                var count = 1;
                var total_sales_tax_amount = 0;
                $('.sales_tax_amount').each(function () {

                    total_sales_tax_amount += +$('#sales_tax_amount_1_' + count).val();
                    count++;
                });

                total_sales_tax_amount=parseFloat(total_sales_tax_amount).toFixed(2);
                $('#total_sales_tax_amount').val(total_sales_tax_amount);

                var count = 1;
                var net_amount = 0;
                $('.net_amount').each(function () {

                    net_amount += +$('#net_amount_1_' + count).val();
                    count++;
                });
                net_amount=parseFloat(net_amount).toFixed(2);
                $('#net_amount').val(net_amount);
                var total_amount = $('#net_amount').val();

                $('#d_t_amount_1').val(total_amount);
                net_amount_func();
                toWords(1);
                sales_tax_amount_display(number);
            }



            function tax_by_amount(id)
            {

                var  number= id.replace("sales_tax_amounttd_1_","");
                var tax_percentage=$('#accounts_1_'+number).val();



                if (tax_percentage==0)
                {

                    $('#'+id).val(0);
                }
                else
                {
                    var tax_amount=parseFloat($('#'+id).val());


                    // highlight
                    var tax_amount= $('#'+id).val();
                    tax_amount= tax_amount=tax_amount.replace(/,/g, "");
                    tax_amount=parseFloat(tax_amount);
                    // highlight end

                    if (isNaN(tax_amount)==true)
                    {
                        tax_amount=0;
                    }
                    var amount=parseFloat($('#amount_1_'+number).val());
                    var total=parseFloat(tax_amount+amount).toFixed(2);


                    $('#net_amount_1_'+number).val(total);
                    // for td net amount for comma seprate 17-nov-2018
                    $('#net_amounttd_1_'+number).val(total);
                    formate_number('net_amounttd_1_'+number,number,'net_amount_1_'+number);

                    // for td net amount for comma seprate 17-nov-2018 End

                    var sales_tax_amount=$('#sales_tax_amount_1_'+number).val();
                    $('#sales_tax_dept_hidden_amount'+number).val(sales_tax_amount);

                    var count = 1;
                    var net_amount = 0;


                    $('.net_amount').each(function () {

                        net_amount += +$('#net_amount_1_' + count).val();
                        count++;
                    });
                    net_amount=parseFloat(net_amount).toFixed(2);
                    $('#net_amount').val(net_amount);

                }
                toWords(1);
                net_amount_func();
                sales_tax_amount_display(number);

            }

            function dept_amount_validation()
            {
                var auth=1;

                for (i=1; i<=x; i++)
                {
                    var item_amount=$('#amount_1_'+i).val();
                    item_amount=item_amount.replace(/,/g, "");
                    item_amount= parseFloat(item_amount);
                    var dept_amount=$('#total_dept'+i).val();

                    item_amount=Math.round(item_amount);
                    dept_amount=Math.round(dept_amount);


                    if (item_amount!=dept_amount)
                    {

                        if($('#dept_check_box'+i).is(":checked"))
                        {


                        }
                        else
                        {
                            var dept_name = $('#category_id_1_' + i + ' :selected').text();
                            alert('Department Allocation Not Macth For ' + dept_name);
                            auth=0;
                            return false;

                        }
                    }

                }
                return auth;
            }



            function sales_tax_amount_validation()
            {
                var auth=1;

                for (i=1; i<=x; i++)
                {
                    var item_amount=$('#sales_tax_amount_1_'+i).val();

                    item_amount=item_amount.replace(/,/g, "");
                    item_amount= parseFloat(item_amount);
                    var dept_amount=$('#sales_tax_total_dept'+i).val();


                    item_amount=Math.round(item_amount);
                    dept_amount=Math.round(dept_amount);
                    if (item_amount!=dept_amount)
                    {

                        if($('#sales_tax_check_box'+i).is(":checked"))
                        {


                        }
                        else
                        {
                            var acc_name = $('#accounts_1_' + i + ' :selected').text();
                            alert('Sales Tax Department Allocation Not Macth For ' + acc_name);
                            auth=0;
                            return false;

                        }
                    }

                }
                return auth;
            }


            function cost_center_amount_validation()
            {
                var auth=1;

                for (i=1; i<=x; i++)
                {
                    var item_amount=$('#amount_1_'+i).val();
                    item_amount=item_amount.replace(/,/g, "");
                    item_amount= parseFloat(item_amount);
                    var cost_center=$('#cost_center_total_dept'+i).val();


                    item_amount=Math.round(item_amount);
                    cost_center=Math.round(cost_center);

                    if (item_amount!=cost_center)
                    {

                        if($('#cost_center_check_box'+i).is(":checked"))
                        {


                        }
                        else
                        {
                            var dept_name = $('#category_id_1_' + i + ' :selected').text();
                            alert('Cost Center Allocation Not Macth For ' + dept_name);
                            auth=0;
                            return false;

                        }
                    }

                }
                return auth;
            }


            function change_day()
            {

                var date=$('#demand_date_1').val();

                var d = new Date(date);

                var weekday = new Array(7);
                weekday[0] = "Sunday";
                weekday[1] = "Monday";
                weekday[2] = "Tuesday";
                weekday[3] = "Wednesday";
                weekday[4] = "Thursday";
                weekday[5] = "Friday";
                weekday[6] = "Saturday";

                var n = weekday[d.getDay()];

                document.getElementById("pv_day").value = n;
            }




            function net_amount_func()
            {
                 var count=1;
                var net_amount=0;
                $('.net_amount').each(function () {

                    net_amount += +$('#net_amount_1_' + count).val();
                    count++;
                });

                $('#net_amounttd').val(net_amount);
            }
        </script>




        <script type="text/javascript">

            $('.select2').select2();
        </script>

        <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection