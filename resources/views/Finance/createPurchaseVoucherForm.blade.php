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

$pv_no=CommonHelper::uniqe_no_for_purcahseVoucher(date('y'),date('m'));
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

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
       
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Purchase Voucher Forms</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'fad/addPaymentVoucherDetail','id'=>'cashPaymentVoucherForm'));?>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                <label class="sf-label">Pv No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly autofocus type="text" class="form-control requiredField"  placeholder="" name="pv_no" id="pv_no" value="{{$pv_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                <label class="sf-label">Pv Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input onblur="change_day()" onchange="change_day()" type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="purchase_date" id="demand_date_1" value="<?php echo date('Y-m-d') ?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                <label class="sf-label">Pv Day.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Ref / Bill No" name="slip_no" id="slip_no_1" value="" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="sf-label">Bill Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField"  name="bill_date" id="bill_date" value="<?php echo date('Y-m-d') ?>" />
                                            </div>
                                        </div>

                                    </div>
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input value="<?php echo date('Y-m-d'); ?>" type="date" name="due_date" id="due_date" class="form-control"/>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label"><a tabindex="-1" href="#" onclick="showDetailModelOneParamerter('pdc/createPurchaseTypeForm')" class="">Purchase Type</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select  class="form-control  select2" name="p_type" id="p_type">
                                                    <option value="">Select Demand Type</option>
                                                    @foreach(CommonHelper::get_all_purchase_type() as $row)
                                                        <option value="{{$row->id}}">{{ucwords($row->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');" class="">Vendor</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_current_amount(this.id);get_advance_amount()" name="supplier" id="supplier" class="form-control select2 requiredField">
                                                    <option value=""> SELECT</option>

                                                    @foreach($supplier as $row)
                                                        <option value="{{$row->id}}">{{ucwords($row->name)}}</option>
                                                    @endforeach;

                                                </select>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Advance <span class="rflabelsteric"><strong></strong></span></label>
                                                <input readonly  type="number" class="form-control " placeholder="" name="advance" id="advance" value="" />
                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <div class="lineHeight">&nbsp;</div>

                                <h4 style="text-align: center">Purchase Voucher Data</h4>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                            <label for="">
                                                With Item
                                                <input type="radio" name="item" id="" class="form-control" value="1" onclick="RadioVal(this.value)">
                                            </label>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                            <label for="">
                                                Without Item
                                                <input type="radio" name="item" id="" checked class="form-control" value="2" onclick="RadioVal(this.value)">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ChangeCol">
                                        <div  class="table-responsive">
                                            <div  class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                                                <table  id="" class="table table-bordered">
                                                    <thead>
                                                    <tr>

                                                        <th  class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax/category_id_1_1')" class="">Acc. Head</a>
                                                            <strong>*</strong></span>
                                                        </th>
                                                        <th  class="text-center hidden-print ShowHide"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
                                                        <th  class="text-center ShowHide">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th  class="text-center ShowHide">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th  class="text-center ShowHide">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th  class="text-center">Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th  class="text-center">Action <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="TrAppend">
                                                    <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                                                    <tr>
                                                        <td>

                                                            <!--
                                                                <select style="width: 100%" name="category_id_1_1" id="category_id_1_1" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField select2">
                                                                    <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                                                    </select>
                                                                                            -->
                                                            <select  style="width: 100%" class="form-control requiredField select2"  id="category_id_1_1" class="form-control requiredField" name="category_id_1_1" onchange="">
                                                                <option value="">Select Expense</option>

                                                                <?php

                                                                ?>
                                                                @foreach(FinanceHelper::get_accounts() as $row)
                                                                    <option value="{{ $row->id}}">{{ ucwords($row->name)}}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                        <td class="ShowHide">
                                                            <select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_1" id="sub_item_id_1_1" class="form-control select2">
                                                                <option value="">Select</option>

                                                                @foreach(CommonHelper::get_all_subitem() as $row)
                                                                    <option value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="ShowHide">
                                                            <input type="text" name="uom_1_1" id="uom_1_1" class="form-control" />
                                                            <input type="hidden" name="uom_id_1_1" id="uom_id_1_1" class="form-control" />
                                                        </td>

                                                        <td class="ShowHide">
                                                            <input onkeyup="calculation_amount(this.id,'1')"  type="number" step="0.01" name="qty_1_1" id="qty_1_1" class="form-control qty" />
                                                        </td>

                                                        <td class="ShowHide">
                                                            <input  onkeyup="calculation_amount(this.id,'1')" type="number" step="0.01" name="rate_1_1" id="rate_1_1" class="form-control rate" />
                                                        </td>

                                                        <td>
                                                            <input onkeyup="pick_amount(this.id,'amount_1_1');calc_amount(this.id)" type="text"  name="amounttd_1_1" id="amounttd_1_1" class="form-control requiredField amount" />
                                                        </td>

                                                        <!-->
                                                    </tr>
                                                    </tbody>
                                                    <tbody>
                                                    <tr>

                                                        <td class="text-center" id="AddRemColSpan">TOTAL</td>
                                                        <td  class=""  ><input tabindex="-1" type="text" maxlength="15" class="form-control text-right" name="total_net_amounttd" value="" id="net_amounttd" readonly=""></td>
                                                        <input type="hidden" name="total_net_amount" id="net_amount" value=""/>
                                                        <input type="hidden" name="d_t_amount_1" id="d_t_amount_1" value=""/>

                                                    </tr>
                                                    </tbody>


                                                </table>

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

                                </table>

                                <table>
                                    <tr>

                                        <td id="rupees<?php ?>"></td>
                                        <input type="hidden" name="rupeess" id="rupeess"/>
                                    </tr>






                                </table>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">

                                            <tr>
                                                <td><b>Adjust Advance Amount</b></td>
                                                <td class="text-center"></td>
                                                <td class="text-right"><input type="text" class="form-control number" onkeyup="adjust_advnace_amont()" name="advance_from_customer" placeholder="Advance From Customer" id="advance_from_customer" value="" readonly /> </td>

                                            </tr>



                                            <?php /*?>
                                            <tr>
                                                <td><b>Sales Tax</b></td>
                                                <td><input type="text" class="form-control number" onkeyup="sales_tax_calc()" id="sales_tax_percent" name="sales_tax_percent" placeholder="Sales Tax Percent"  /></td>
                                                <td class="text-right"><input onkeyup="sales_tax_percent_calc()" type="text" class="form-control number" name="sales_tax_amount" placeholder="Sales Tax Amount" id="sales_tax_amount" /> </td>
                                            </tr>

                                            <tr>
                                                <td>Sales Tax Account</td>
                                                <td colspan="2">
                                                    <select name="AccId" id="AccId" class="form-control select2" disabled>
                                                        <option value="">Select Account</option>
                                                        <?php foreach(FinanceHelper::get_accounts() as $row):?>
                                                        <option value="<?php echo $row->id?>"><?php echo  $row->code .' ---- '. $row->name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php */?>




                                            <tr style="background-color: darkgray">
                                                <td colspan="2"><b>Net Amount</b></td>
                                                <td class="text-right"><input style="font-size: larger;font-weight: bold"  type="text" class="form-control number" id="totalAmount" readonly /> </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreRows()" value="Add More Demand's Rows" />
                                    </div>
                                </div>


                                <!--department-->

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Description</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="description" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                                        </div>
                                    </div>
                                </div>
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
        </div>


        <script>

            $(document).ready(function() {
                var d = new Date();

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



            });
        </script>




        <script type="text/javascript">
            $(document).ready(function(){
                $('.ShowHide').css('display','none');
                $('#AddRemColSpan').attr('colspan',0);
//                $('#ChangeCol').removeClass();
//                $('#ChangeCol').addClass('col-lg-4 col-md-4 col-sm-4 col-xs-12');
            });

            $('.select2').select2();

            function RemoveRows(count)
            {
                $("#tr"+count).remove();
            }

            function change_day() {

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
            }
        </script>
        <script>
            function get_supplier(id)
            {
                var supplier= $('#'+id).val();
                supplier_data=supplier.split(',');
                supplier=supplier_data[1];
                if (supplier!=0)
                {
                    var supplier_text_data=$("#payment_id option:selected").text();
                    var supplier_text_data=supplier_text_data.split('=>');
                    $('#adv_amount').val(supplier_text_data[3]);
                    $('#supplier').val([1,supplier]).trigger('change');


                }
                else
                {

                    $('#supplier').val([0,0]).trigger('change');
                    $('#adv_amount').val(0);
                }
            }

            var CounterRow = 1;
            function AddMoreRows()
            {
                  var val = $('input[name="item"]:checked').val();

                CounterRow++;
                $('#TrAppend').append('<tr id="tr'+CounterRow+'" ><td>' +
                                '<input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="'+CounterRow+'" />'+
                        '<select style="width: 100%" class="form-control requiredField select2"  id="category_id_1_'+CounterRow+'" class="form-control requiredField" name="category_id_1_'+CounterRow+'" onchange="">'+
                  '<option value="">Select Expense</option>@foreach(FinanceHelper::get_accounts() as $row)<option value="{{ $row->id}}">{{ ucwords($row->name)}}</option>@endforeach</select>'+
                    '</td>'+
                    '<td class="ShowHide"><select  onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_'+CounterRow+'" id="sub_item_id_1_'+CounterRow+'" class="form-control select2">'+
                    '<option value="">Select</option>'+
                    '@foreach(CommonHelper::get_all_subitem() as $row)'+
                     '<option value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>'+
                    '@endforeach'+
                    '</select>'+
                    '</td>'+
                    '<td class="ShowHide">'+
                    '<input type="text" name="uom_1_'+CounterRow+'" id="uom_1_'+CounterRow+'" class="form-control" />'+
                    '<input type="hidden" name="uom_id_1_'+CounterRow+'" id="uom_id_1_'+CounterRow+'" class="form-control" />'+
                    '</td>'+
                    '<td class="ShowHide" >'+
                    '<input onkeyup="calculation_amount(this.id,'+CounterRow+')"  type="number" step="0.01" name="qty_1_'+CounterRow+'" id="qty_1_'+CounterRow+'" class="form-control qty" />'+
                    '</td>'+
                    '<td class="ShowHide">'+
                    '<input onkeyup="calculation_amount(this.id,'+CounterRow+')" type="number" step="0.01" name="rate_1_'+CounterRow+'" id="rate_1_'+CounterRow+'" class="form-control rate" />'+
                    '</td>'+
                    '<td>'+
                    '<input onkeyup=pick_amount(this.id,"amount_1_1");calc_amount(this.id) type="text"  name="amounttd_1_'+CounterRow+'" id="amounttd_1_'+CounterRow+'" class="form-control requiredField amount" />'+
                    '<input type="hidden" step="0.01" name="amount_1_'+CounterRow+'" id="amount_1_'+CounterRow+'"/>'+
                    '</td>'+
                    '<td>'+
                    '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove" onclick="RemoveRows('+CounterRow+')">Remove Rows</button>'+
                    '</td>'+
                    '</tr>');
                $('.select2').select2();
                if(val == 1)
                {
                    $('.ShowHide').fadeIn('fast');
                    $('#AddRemColSpan').attr('colspan',5);
//                    $('#ChangeCol').removeClass();
//                    $('#ChangeCol').addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12');

                }
                else
                {
                    $('.ShowHide').css('display','none');
                    $('#AddRemColSpan').attr('colspan',0);
//                    $('#ChangeCol').removeClass();
//                    $('#ChangeCol').addClass('col-lg-4 col-md-4 col-sm-4 col-xs-12');
                }
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
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/get_detail_purchase_voucher',
                    type: "GET",
                    data: { id:id},
                    success:function(data) {

                        data=data.split('*');

                        $('#uom_1_'+number).val(data[0]);
                        $('#rate_1_'+number).val(data[1]);
                        $('#uom_id_1_'+number).val(data[2]);
                    }
                });
            }


            $(".btn-success").click(function(e){
                jqueryValidationCustom();
                if(validate == 0){

                    $('#BtnSubmit').css('display','none');
                    //return false;
                }else{
                    return false;
                }
            });

            function calc_amount(id)
            {
                sum_amount=0;
                $("input[class *= 'amount']").each(function(){
                    totalamount = $(this).val();
                    sum_amount += +totalamount;
                });
                $('#net_amounttd').val(sum_amount);
//                var SalesTaxAmount = parseFloat($("#sales_tax_amount").val());
//                $('#totalAmount').val(sum_amount+SalesTaxAmount);
//                var sales_tax_percent=parseFloat($('#sales_tax_percent').val());
//                if (isNaN(sales_tax_percent))
//                {
//                    sales_tax_percent=0;
//                    $('#sales_tax_percent').val(sales_tax_percent);
//                }
//                sales_tax_calc();
//                sales_tax_percent_calc();
            }

            function sales_tax_calc()
            {

                var sales_tax_percent=parseFloat($('#sales_tax_percent').val());
                var NetAmount = parseFloat($('#net_amounttd').val());


                if(sales_tax_percent > 0 )
                {
                    $('#AccId').prop('disabled',false);
                    $('#AccId').addClass('requiredField');
                }
                else{
                    $('#AccId').prop('disabled',true);
                    $('#AccId').removeClass('requiredField');
                }
                sales_tax_percent=(sales_tax_percent).toFixed(2);
                if (isNaN(sales_tax_percent))
                {
                    sales_tax_percent=0;
                }
                var sales_tax_amount=(NetAmount/100)*sales_tax_percent;
                sales_tax_amount=parseFloat((sales_tax_amount).toFixed(2));

                $('#sales_tax_amount').val(sales_tax_amount);
                var TotAmount = parseFloat(NetAmount+sales_tax_amount);
                $('#totalAmount').val(TotAmount);

            }

            function sales_tax_percent_calc()
            {
                var SaleTxAmnt = parseFloat($('#sales_tax_amount').val());

                if(SaleTxAmnt > 0 )
                {
                    $('#AccId').prop('disabled',false);
                    $('#AccId').addClass('requiredField');
                }
                else{
                    $('#AccId').prop('disabled',true);
                    $('#AccId').removeClass('requiredField');
                }

                var sales_tax_amount_amount=parseFloat($('#sales_tax_amount').val());
                var NetAmount = parseFloat($('#net_amounttd').val());
                var sales_tax_percent_percent=parseFloat(sales_tax_amount_amount/NetAmount)*100;
                var sales_tax_percent_percent=(sales_tax_percent_percent).toFixed(2);
                $('#sales_tax_percent').val(sales_tax_percent_percent);
                var TotAmount = parseFloat(NetAmount+SaleTxAmnt);
                $('#totalAmount').val(TotAmount);

            }

            function calculation_amount(id,i)
            {
                sum_amount=0;
                qty = $('#qty_1_'+i).val();
                rate = $('#rate_1_'+i).val();
                amt = qty*rate;
                $('#amounttd_1_'+i).val(amt);

                $("input[class *= 'amount']").each(function(){
                    totalamount = $(this).val();
                    sum_amount += +totalamount;
                });
                $('#net_amounttd').val(sum_amount);

            }

            function RadioVal(Val)
            {
                if(Val == 1)
                {
                    $('.ShowHide').fadeIn('fast');
                    $('#AddRemColSpan').attr('colspan',5);
//                    $('#ChangeCol').removeClass();
//                    $('#ChangeCol').addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12');
                }
                else
                {
                    $('.ShowHide').css('display','none');
                    $('#AddRemColSpan').attr('colspan',0);
//                    $('#ChangeCol').removeClass();
//                    $('#ChangeCol').addClass('col-lg-4 col-md-4 col-sm-4 col-xs-12');
                }
            }

            function adjust_advnace_amont()
            {
                var advance_from_customer=  $('#advance_from_customer').val();
                var net_amount=  $('#net_amounttd').val();
                var total=parseFloat(net_amount-advance_from_customer);
                $('#totalAmount').val(total);
            }

            function get_advance_amount()
            {

                var supplier_id= $('#supplier').val();

                $.ajax({
                    url: '{{url('/get_advance_amount')}}',
                    type: "GET",
                    data: { supplier_id:supplier_id},
                    success:function(data)
                    {

                        $('#advance').val(data);
                    }
                });
            }

        </script>
        <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection