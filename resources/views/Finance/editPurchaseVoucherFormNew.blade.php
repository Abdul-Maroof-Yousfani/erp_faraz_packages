<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;

use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$WithItem = '';
$WithOutItem = '';
        if($CountId !="" || $CountId != 0)
        {
            $WithItem ='checked';
            $WithOutItem = '';
        }
        else
        {
            $WithItem ='';
            $WithOutItem = 'checked';
        }


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

        <div class="row well_N">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Edit Purchase Voucher Forms</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'fad/updatePurchaseVoucher','id'=>'cashPaymentVoucherForm'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="EditId" id="EditId" value="<?php echo $id?>">
                        <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Pv No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly autofocus type="text" class="form-control requiredField"  placeholder="" name="pv_no" id="pv_no" value="{{$NewPurchaseVoucher->pv_no}}" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Pv Date.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input onblur="change_day()" onchange="change_day()" type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="purchase_date" id="demand_date_1" value="<?php echo $NewPurchaseVoucher->pv_date ?>" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Pv Day.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly autofocus type="text" class="form-control requiredField" placeholder="Ref / Bill No" name="slip_no" id="slip_no_1" value="<?php echo $NewPurchaseVoucher->slip_no?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Bill Date.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="date" class="form-control requiredField"  name="bill_date" id="bill_date" value="<?php echo $NewPurchaseVoucher->bill_date ?>" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Due Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly value="<?php echo $NewPurchaseVoucher->due_date; ?>" type="date" name="due_date" id="due_date" class="form-control"/>
                                        </div>

                                

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                            <label class="sf-label"><a tabindex="-1" href="#" onclick="showDetailModelOneParamerter('pdc/createPurchaseTypeForm')" class="">Purchase Type</a></label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select readonly class="form-control  select2" name="p_type" id="p_type">
                                                <option value="">Select Demand Type</option>
                                                @foreach(CommonHelper::get_all_purchase_type() as $row)
                                                    <option value="{{$row->id}}" <?php if($NewPurchaseVoucher->purchase_type == $row->id):echo"selected";endif;?>>{{ucwords($row->name)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');" class="">Supplier</a></label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select disabled onchange="get_current_amount(this.id)" class="form-control select2 requiredField">
                                                <option value=""> Select</option>
                                                @foreach($supplier as $row)
                                                    <option value="{{$row->id}}" <?php if($NewPurchaseVoucher->supplier == $row->id):echo "selected";endif;?>>{{ucwords($row->name)}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="supplier" name="supplier" value="{{$NewPurchaseVoucher->supplier}}"/>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Supplier Current Amount <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly type="number" class="form-control requiredField" placeholder="" name="current_amount" id="current_amount" value="" />
                                        </div> 
                                    </div>
                                    <div class="row">
                                        @if ($NewPurchaseVoucher->grn_no!='')
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">GRN No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly  type="text" class="form-control requiredField" placeholder="" name="grn_no" id="grn_no" value="{{$NewPurchaseVoucher->grn_no}}" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>

                                    <!-- <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <label for="">
                                                    With Item
                                                    <input type="radio" name="item" id="" class="form-control" value="1" onclick="RadioVal(this.value)" >
                                                </label>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <label for="">
                                                    Without Item
                                                    <input type="radio" name="item" id=""  class="form-control" value="2" onclick="RadioVal(this.value)" >
                                                </label>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ChangeCol">
                                            <div  class="table-responsive">
                                                <div  class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                                                    <table  id="" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <!-- <th  class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax/category_id_1_1')" class="">Acc. Head</a>
                                                                <strong>*</strong></span>
                                                            </th> -->
                                                            <th  class="text-center hidden-print ShowHide"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
                                                            <th  class="text-center ShowHide">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th  class="text-center ShowHide">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th  class="text-center ShowHide">Return Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th  class="text-center ShowHide">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th  class="text-center hide">Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th  class="text-center">Net Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="TrAppend">

                                                        <?php
                                                      
                                                        $Counter = 0;
                                                        $TotalAmount = 0;
                                                        $currency = 0;
                                                        ?>
                                                        @foreach($NewPurchaseVoucherData as $DFil)
                                                            <?php 
                                                            $Counter++;

                                                            $return_qty= ReuseableCode::purchase_return_qty($DFil->id);
                                                            $qty=$DFil->purchase_recived_qty-$DFil->qc_qty;
                                                            $actual_qty=$qty-$return_qty;
    
                                                            $rate=$DFil->rate;
    
                                                            $amount=$actual_qty*$rate *$currency ;
                                                            $discount_percent= $DFil->discount_percent;
    
                                                            if ($discount_percent>0):
                                                                 $discount_amount=($amount / 100)*$discount_percent;
                                                            else:
                                                                $discount_amount=0;
                                                            endif;

                                                            ?>
                                                            <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                                                            <input type="hidden" name="grn_data_id_<?php echo $Counter?>" value="{{$DFil->grn_data_id}}" />
                                                            <tr>
                                                                <!-- <td>
                                                                    <select  style="width: 100%" class="form-control requiredField select2"  id="category_id_1_<?php echo $Counter?>" class="form-control requiredField" name="category_id_1_<?php echo $Counter?>" onchange="">
                                                                        <option value="">Select Expense</option>

                                                
                                                                        @foreach(FinanceHelper::get_accounts() as $row)
                                                                            <option value="{{ $row->id}}" <?php if($DFil->category_id == $row->id):echo "selected";endif;?>>{{ ucwords($row->name)}}</option>
                                                                        @endforeach

                                                                    </select>
                                                                </td> -->
                                                                <!-- <td class="ShowHide">
                                                                    <select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_<?php echo $Counter?>" id="sub_item_id_1_<?php echo $Counter?>" class="form-control select2">
                                                                        <option value="">Select</option>

                                                                        @foreach(CommonHelper::get_all_subitem() as $row)
                                                                            <option value="{{ $row->id }}"<?php if($DFil->sub_item == $row->id):echo "selected"; endif;?>>{{ ucwords($row->sub_ic) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td> -->
                                                                <td title="{{CommonHelper::get_item_name($DFil->sub_item_id)}}" class="text-center" style="width: 30%;">
                                                                    <input type="hidden" name="sub_item_id_1_<?php echo $Counter; ?>" value="{{$DFil->sub_item}}"/>


                                                                    <?php
                                                                    echo CommonHelper::get_item_name($DFil->sub_item);
                                                                    ?>
                                                                </td>
                                                                <td class="ShowHide">
                                                                    <input type="text" readonly name="uom_1_<?php echo $Counter?>" id="uom_1_<?php echo $Counter?>" class="form-control" value="<?php echo CommonHelper::get_uom_name($DFil->uom)?>" />
                                                                    <input type="hidden" name="uom_id_1_<?php echo $Counter?>" id="uom_id_1_<?php echo $Counter?>" class="form-control" value="<?php echo $DFil->uom?>" />
                                                                </td>

                                                                <td class="ShowHide">
                                                                    <input    @if ($NewPurchaseVoucher->grn_no!='') readonly @endif onkeyup="calculation_amount(this.id,'<?php echo $Counter?>')"  type="number" step="0.01" name="qty_1_<?php echo $Counter?>" id="qty_1_<?php echo $Counter?>" class="form-control qty" value="<?php echo $DFil->qty?>" />
                                                                </td>
                                                                <td>
                                                                    <input readonly value="{{$return_qty}}"  type="number" step="0.01" name="return_qty_1_<?php echo $Counter ?>" id="qty_1_<?php echo $Counter ?>" class="form-control qty" />
                                                                </td>

                                                                <td class="ShowHide">
                                                                    <input  readonly onkeyup="calculation_amount(this.id,'<?php echo $Counter?>')" type="number" step="0.01" name="rate_1_<?php echo $Counter?>" id="rate_1_<?php echo $Counter?>" class="form-control rate" value="<?php echo $DFil->rate?>" />
                                                                </td>

                                                                <td class="hide">
                                                                    <input  onkeyup="pick_amount(this.id,'amount_1_<?php echo $Counter?>');calc_amount(this.id)" type="text"  name="amounttd_1_<?php echo $Counter?>" id="amounttd_1_<?php echo $Counter?>" class="form-control requiredField amount" value="<?php echo $DFil->amount; $TotalAmount+=$DFil->amount;?>" />
                                                                </td>
                                                                <td >
                                                                    <input readonly type="text" class="form-control" id="net_amoun{{ $Counter }}" name="net_amount{{$Counter}}" value="{{$DFil->net_amount}}">
                                                                </td>
                                                            </tr>
                                                         @endforeach
                                                         </tbody>
                                                        <tr>

                                                            <td class="text-center" colspan="4"></td>
                                                            <td class="text-center" colspan="1">Total</td>
                                                            <td  class=""  ><input tabindex="-1" type="text" maxlength="15" class="form-control text-right" name="total_net_amounttd" value="<?php echo $TotalAmount?>" id="net_amounttd" readonly=""></td>
                                                            <input type="hidden" name="total_net_amount" id="net_amount" value=""/>
                                                            <input type="hidden" name="d_t_amount_1" id="d_t_amount_1" value=""/>
                                                        </tr>
                                                        <table class="table">
                                                        
                                                            @if($NewPurchaseVoucher->grn_no !="")
                                                                <tr>
                                                                    <td class="text-center" colspan="1"></td>
                                                                    <td colspan="1">Sales Taxes</td>
                                                                    <td colspan="3">
                                                                        <select name="SalesTaxesAccId" class="form-control" id="SalesTaxesAccId" onchange="sales_tax_calc()">
                                                                            <option value="">Select Head</option>
                                                                            @foreach(FinanceHelper::get_accounts() as $row)
                                                                                <option value="<?php echo $row->id?>" <?php if($NewPurchaseVoucher->sales_tax_acc_id == $row->id){echo "selected";}?>>{{ ucwords($row->name)}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input readonly type="text" name="SalesTaxAmount" id="SalesTaxAmount" class="form-control text-right" value="<?php echo $NewPurchaseVoucher->sales_tax_amount?>" onkeyup="sales_tax_calc()" <?php if($NewPurchaseVoucher->sales_tax_acc_id ==0){echo "disabled";}?>></td>
                                                                </tr>

                                                                <tr>
                                                                    <td id="rupees" class="text-center" colspan="4"></td>
                                                                    <td class="text-center" colspan="1">Net Total</td>
                                                                    <td colspan="2"><input tabindex="-1" type="text" maxlength="15" class="form-control text-right" name="total_net_amounttd" value="<?php echo $TotalAmount+$NewPurchaseVoucher->sales_tax_amount?>" id="TotalAfterSalesTaxAmount" readonly=""></td>
                                                                    <input type="hidden" name="total_net_amount" id="net_amount" value=""/>
                                                                    <input type="hidden" name="d_t_amount_1" id="d_t_amount_1" value=""/>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <table>
                                        <tr>
                                            <td id="rupees<?php ?>"></td>
                                            <input type="hidden" name="rupeess" id="rupeess"/>
                                        </tr>
                                    </table>
                                    @if ($NewPurchaseVoucher->grn_no=='')
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreRows()" value="Add More Demand's Rows" />
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Description</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="description" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField"><?php echo $NewPurchaseVoucher->description?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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


    <script>
        function pick_amount(id,send) {
            var current_amount=$('#'+id).val();
            $('#'+send).val(current_amount);
        }

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
        $('#demandDataSection_1').val(CounterRow);


        });
    </script>




    <script type="text/javascript">
        $(document).ready(function(){
            toWordss(i);
            <?php if($WithItem=='checked'):?>
            RadioVal('1');
            <?php else:?>
            $('.ShowHide').css('display','none');
            $('#AddRemColSpan').attr('colspan',0);
            <?php endif;?>


            change_day();


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

            }
            else
            {
                $('.ShowHide').css('display','none');
                $('#AddRemColSpan').attr('colspan',0);
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

        function calc_amount(id)
        {
            sum_amount=0;
            $("input[class *= 'amount']").each(function(){
                totalamount = $(this).val();
                sum_amount += +totalamount;
            });
            $('#net_amounttd').val(sum_amount);

        }

        function sales_tax_calc()
        {
            var SalesTaxAmount = parseFloat($('#SalesTaxAmount').val());
            var NetAmount = parseFloat($('#net_amounttd').val());
            var AccId = $('#SalesTaxesAccId').val();
            if(AccId !='')
            {$('#SalesTaxAmount').prop('disabled',false);}
            else{$('#SalesTaxAmount').prop('disabled',true);
                SalesTaxAmount =0;
                $('#SalesTaxAmount').val(0)}


            $('#TotalAfterSalesTaxAmount').val(parseFloat(NetAmount+SalesTaxAmount).toFixed(2));
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

        function CalcAmount()
        {
            var TotalAmount = $('#TotalAmount').val();
            var SalesTaxAmount = $('#SalesTaxAmount').val();

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
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection