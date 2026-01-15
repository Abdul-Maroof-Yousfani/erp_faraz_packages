<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')


    <style>
        * {
            font-size: 12px!important;

        }
        label {
            text-transform: capitalize;
        }
    </style>


    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Sales Tax Invoice</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'sad/updateSalesTaxInvoice?m='.$m.'','id'=>'createSalesOrder'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php // echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php // echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php
                                            $gi_no=$sales_tax_invoice->so_no;
                                            $so_date=$sales_tax_invoice->so_date;
                                            $gi_no=str_replace("SO","GI",$gi_no);
                                            ?>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Invoice No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="gi_no" id="gi_no" value="{{strtoupper($gi_no)}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Invoice Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  autofocus type="date" class="form-control requiredField" placeholder="" name="gi_date" id="gi_date" value="{{$so_date}}" />
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Delivery Note No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="gd_no" id="gd_no" value="{{strtoupper($sales_tax_invoice->gd_no)}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Delivery Note Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly autofocus type="date" class="form-control requiredField" placeholder="" name="gd_date" id="gd_date" value="{{$sales_tax_invoice->gd_date}}" />
                                            </div>



                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">SO NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="so_no" id="so_no" value="{{$sales_tax_invoice->so_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">SO Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="date" class="form-control requiredField" placeholder="" name="so_date" id="so_date" value="{{$sales_tax_invoice->so_date}}" />
                                            </div>



                                        </div>


                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label  class="sf-label">Mode / Terms Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="model_terms_of_payment" id="model_terms_of_payment" value="{{$sales_tax_invoice->model_terms_of_payment}}" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Other Reference(s) <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="other_refrence" id="other_refrence" value="{{$sales_tax_invoice->other_refrence}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="order_no" id="order_no" value="{{$sales_tax_invoice->order_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="date" class="form-control requiredField" placeholder="" name="order_date" id="order_date" value="{{$sales_tax_invoice->order_date}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched Document No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly  type="text" class="form-control" placeholder="" name="despacth_document_no" id="despacth_document_no" value="{{$sales_tax_invoice->despacth_document_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched Document Date</label>
                                                <input readonly  type="date" class="form-control" placeholder="" name="despacth_document_date"  id="despacth_document_date" value="{{$sales_tax_invoice->despacth_document_date}}" />
                                            </div>




                                        </div>

                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched through<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="despacth_through" id="despacth_through" value="{{$sales_tax_invoice->desptch_through}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Destination<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="destination" id="destination" value="{{$sales_tax_invoice->destination}}" />
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Terms Of Delivery<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="terms_of_delivery" id="terms_of_delivery" value="{{$sales_tax_invoice->terms_of_delivery}}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Buyer's Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select disabled name="" id="ntn" onchange="get_ntn()" class="form-control select2">
                                                    <option>Select</option>
                                                    @foreach(SalesHelper::get_all_customer() as $row)
                                                        <option @if($sales_tax_invoice->buyers_id==$row->id) selected @endif value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" name="buyers_id" value="{{$sales_tax_invoice->buyers_id}}"/>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Buyer's Ntn </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_ntn" id="buyers_ntn" value="" />
                                            </div>



                                        </div>

                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Sales Tax No </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="date" class="form-control requiredField" placeholder="" name="due_date" id="due_date" value="{{$sales_tax_invoice->due_date}}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Account<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select class="form-control" id="acc_id" name="acc_id">
                                                    @foreach(FinanceHelper::get_accounts() as $row)
                                                        <option @if($row->id==$sales_tax_invoice->acc_id) selected @endif  value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="demand_type" id="demand_type">
                                        <div class="row">


                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea  name="description" id="description" rows="4" cols="50" style="resize:none;text-transform: capitalize" class="form-control requiredField">{{$sales_tax_invoice->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span ondblclick="show()" class="subHeadingLabelClass">Sales Tax Invoice Data</span>
                                    <!--
                                    <input type="checkbox" id="amount_data" checked/>
                                    <!-->

                                </div>
                                <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>


                                <div id="addMoreDemandsDetailRows_1" class="panel addMoreDemandsDetailRows_1">

                                    <input type="hidden" name="count" id="count" value="1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">S.NO</th>
                                                    <th class="text-center">Item</th>
                                                    <th class="text-center hidee">Batch</th>
                                                    <th class="text-center" >Uom</th>
                                                    <th class="text-center hidee" >Pack Size</th>
                                                    <th class="text-center">Description</th>


                                                    <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center hidee" >Per PCS item . <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center hidee">Rate</th>
                                                    <th class="text-center hidee">Discount%</th>
                                                    <th class="text-center hidee">Discount Amount</th>
                                                    <th class="text-center hidee">Net Amount</th>


                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $counter=1;
                                                $total=0;
                                                $total_qty=0;
                                                foreach ($sales_tax_invoice_data as $row1)
                                                {


                                                ?>
                                                {{--hidden data--}}
                                                <input type="hidden" name="id" id="id" value="{{$sales_tax_invoice->id}}"/>

                                                <?php

                                                $sale_order_id=Input::get('sales_order_id');
                                                $delivery_note_id=Input::get('delivery_note_id');
                                                ?>
                                                <input type="hidden" name="sales_order_id" id="sales_order_id" value="{{$sale_order_id}}"/>

                                                <input type="hidden" name="delivery_note_id" id="delivery_note_id" value="{{$delivery_note_id}}"/>

                                                <input type="hidden" name="item_id{{$counter}}" id="item_id{{$counter}}" value="{{$row1->item_id}}"/>
                                                <input type="hidden" name="batch_id{{$counter}}" id="batch_id{{$counter}}" value="{{$row1->batch_id}}"/>
                                                <input type="hidden" name="desc{{$counter}}" id="desc{{$counter}}" value="{{$row1->description}}"/>
                                                <input type="hidden" name="qty{{$counter}}" id="qty{{$counter}}" value="{{$row1->qty}}"/>
                                                <input type="hidden" name="per_pcs_item{{$counter}}" id="per_pcs_item{{$counter}}" value="{{$row1->per_pcs_item}}"/>
                                                <input type="hidden" name="rate{{$counter}}" id="rate{{$counter}}" value="{{$row1->rate}}"/>
                                                <input type="hidden" name="discount_percent{{$counter}}" id="discount_percent{{$counter}}" value="{{$row1->discount}}"/>
                                                <input type="hidden" name="discount_amount{{$counter}}" id="discount_amount{{$counter}}" value="{{$row1->discount_amount}}"/>
                                                <input type="hidden" name="amount{{$counter}}" id="amount{{$counter}}" value="{{$row1->amount}}"/>


                                                {{--hidden data End --}}


                                                <tr>
                                                    <td class="text-center" class="text-center"><?php echo $counter;?></td>
                                                    <td class="text-left"><?php echo CommonHelper::get_item_name($row1->item_id);?></td>
                                                    <td class="text-left hidee" class="text-center"><?php echo  $row1->batch_id?></td>

                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                                    ?>
                                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>
                                                    <td class="text-left hidee"> <?php echo $sub_ic_detail[1];?></td>
                                                    <td class="text-center"><textarea name="descr{{$counter}}" id="descr{{$counter}}" class="resize"
                                                                                      style="resize: none" rows="5" cols="50" >{{$row1->description}}</textarea></td>

                                                    <?php $total_qty+=$row1->qty; ?>
                                                    <td class="text-right"> <?php echo number_format($row1->qty,3)?></td>
                                                    <td class="hidee"> <?php echo number_format($row1->per_pcs_item,3)?></td>

                                                    <td class="text-right hidee"><?php echo number_format($row1->rate,2);?></td>
                                                    <td class="text-right hidee"><?php echo number_format($row1->discount_percent,2);?></td>
                                                    <td class="text-right hidee"><?php echo number_format($row1->discount_amount,2);?></td>
                                                    <td class="text-right hidee"><?php echo number_format($row1->amount,2);?></td>

                                                </tr>

                                                <?php

                                                $total+=$row1->amount;
                                                $counter++;

                                                }
                                                ?>
                                                <input type="hidden" name="count" value="{{$counter-1}}"/>
                                                <tr>

                                                    <td id="total_" style="background-color: darkgray" class="text-center" colspan="6">Total</td>
                                                    <td  style="background-color: darkgray" class="text-right"  colspan="1">{{number_format($total_qty,3)}}</td>
                                                    <td  style="background-color: darkgray" class="text-right hidee"  colspan="5">{{number_format($total,2)}}</td>

                                                </tr>


                                                </tbody>
                                                @if($sales_tax_invoice->sales_tax >0)
                                                    <?php  $total+=$sales_tax_invoice->sales_tax; ?>
                                                    <tr class="hidee">
                                                        <td class="text-center" colspan="8"></td>
                                                        <td class="text-right" colspan="6"><b>(Sales Tax 17%)</b> {{   number_format($sales_tax_invoice->sales_tax,2)}}</td>
                                                    </tr>
                                                @endif


                                                @if($sales_tax_invoice->sales_tax_further >0)
                                                    <?php $total+=$sales_tax_invoice->sales_tax_further; ?>
                                                    <tr class="hidee">
                                                        <td class="text-center" colspan="8"></td>
                                                        <td class="text-right" colspan="6"><b>(Sales Tax Further 3%)</b> {{   number_format($sales_tax_invoice->sales_tax_further,2)}}</td>
                                                    </tr>
                                                @endif

                                                <tr class="hidee">

                                                    <td style="background-color: darkgray" class="text-center" colspan="8"></td>
                                                    <td style="background-color: darkgray"  class="text-right" colspan="5"><b>(Grand Total)</b> {{number_format($total,2)}}</td>


                                                </tr>

                                            </table>
                                            <table>
                                                <tr><td style="text-transform: capitalize;">Amount In Words : <?php echo $sales_tax_invoice->amount_in_words ?></td></tr>
                                            </table>
                                            <input type="hidden" name="amount_in_words" id="amount_in_words" value="{{$sales_tax_invoice->amount_in_words}}">

                                        </div>
                                    </div>


                                </div>






                                <table>
                                    <tr>

                                        <td style="text-transform: capitalize;" id="rupees"></td>
                                        <input type="hidden" value="" name="rupeess" id="rupeess1"/>
                                    </tr>
                                </table>
                                <input type="hidden" id="d_t_amount_1" >
                                <!--
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('1')" value="Add More Demand's Rows" />
                                                <input type="button" onclick="removeDemandsRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove">

                                            </div>
                                            <!-->
                            </div>
                        </div>
                    </div>
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
        $(document).ready(function() {

            get_ntn();
            $('#acc_id').select2();
            //	$('.hidee').fadeOut();


            var d = 1;
            $('#qty_1').number(true,3);
            $('#per_pcs_item_1').number(true,2);
            $('#rate_1').number(true,2);
            $('#discount_percent_1').number(true,2);
            $('#discount_amount_1').number(true,2);
            $('#amount_1').number(true,2);
            $('#total').number(true,2);
            $('#per_pcs_item_1').number(true,2);
            $('#sales_tax').number(true);
            $('#sales_tax_further').number(true);
            $('#sales_total').number(true);
            $('#total_after_sales_tax').number(true,2);


            $(".btn-success").click(function(e)
            {

                var demands = new Array();
                var val;
                //	$("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());

                //});
                var _token = $("input[name='_token']").val();

                for (val of demands)
                {

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
        function addMoreDemandsDetailRows(id){
            x++;

            //alert(id+' ---- '+x);
            var m = '<?php echo $_GET['m'];?>';

            $.ajax({
                url: '<?php echo url('/')?>/sdc/addSalesOrder',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {

                    $('.addMoreDemandsDetailRows_'+id+'').append(data);
                    $('#item_id_'+x).select2();
                    $('#batch_id_'+x).select2();
                    $('#item_id_'+x).focus();

                    $('#qty_'+x).number(true,3);
                    $('#per_pcs_item_'+x).number(true,2);
                    $('#rate_'+x).number(true,2);
                    $('#discount_percent_'+x).number(true,2);
                    $('#discount_amount_'+x).number(true,2);
                    $('#amount_'+x).number(true,2);
                    $('#per_pcs_item_'+x).number(true,2);
                    $('#discount_percent_'+x).number(true,2);

                    $('#count').val(x);

                }
            });
        }

        function show()
        {


        }

        $('#amount_data').change(function()
        {

            if($(this).is(':checked'))
            {
                $('.hidee').fadeOut();
                $('#total_').attr('colspan',4);
                $('.resize').attr("cols","50");
            }
            else
            {
                $('.hidee').fadeIn(1000);
                $('.resize').attr("rows","5");
                $('.resize').attr("cols","20");
                $('#total_').attr('colspan',6);
            }

        });

        function amount_calc(id,number)
        {
            var qty=parseFloat($('#qty_'+number).val());
            var rate=parseFloat($('#rate_'+number).val());
            var pack_size=parseFloat($('#pack_size_'+number).val());


            // for amount
            var total=qty * rate;
            $('#amount_'+number).val(total);



            // for per pcs qty
            var pack_size=qty * pack_size;
            $('#per_pcs_item_'+number).val(pack_size);



            // for discount percentage

            if (id=='discount_percent_'+number)
            {


                var discount=parseFloat($('#discount_percent_'+number).val());
                if (discount<=100 && discount >0)
                {
                    var discount_amount = (total / 100) * discount;
                    $('#discount_amount_' + number).val(discount_amount);
                    var amount_total=total-discount_amount;
                    $('#amount_'+number).val(amount_total);
                }
                else
                {
                    $('#discount_percent_'+number).val(0);
                    $('#discount_amount_'+number).val(0);
                }

                // end discount percent
            }
            else
            {
                if (id=='discount_amount_'+number)
                {
                    // for discount amount
                    var discount_amount =parseFloat($('#discount_amount_'+number).val());
                    if (discount_amount>total)
                    {
                        discount_amount=0;
                        $('#discount_amount_'+number).val(0)
                    }

                    var discount_percentage=(discount_amount / total)*100;
                    $('#discount_percent_'+number).val(discount_percentage);
                    var amount_total=total-discount_amount;
                    $('#amount_'+number).val(amount_total);





                }
            }

            net_amount_func();
            sales_tax();

        }


        function net_amount_func(sales_tax_count)
        {


            var net_amount=0;
            $('.amount').each(function (i, obj) {
                var id=(obj.id);

                net_amount += +$('#'+id).val();


            });


            $('#total').val(net_amount);
        }

        function sales_tax()
        {
            var total=	parseFloat($('#total').val());
            var sales_tax=(total/100)*17;
            $('#sales_tax').val(sales_tax);


            var strn= $('#buyers_sales').val();
            if (strn=='')
            {

                var sales_tax_further=(total/100)*3;
                $('#sales_tax_further').val(sales_tax_further);

            }
            else
            {
                sales_tax_further=0;
                $('#sales_tax_further').val(0);
            }

            var total=sales_tax+sales_tax_further;
            $('#sales_total').val(total);

            var total_amount=	parseFloat($('#total').val());
            var total_after_sales_tax=total_amount+total;
            $('#total_after_sales_tax').val(total_after_sales_tax);

            $('#d_t_amount_1').val(total_after_sales_tax);
            toWords(1);
        }


    </script>


    <script>

        function get_batch_detail(id,number) {


            $("#batch_id_"+number).empty().trigger('change')


            //	var number=id.replace("sub_item_id_", "");
            //	number=number.split('_');
            //	number=number[1];


            id=$('#'+id).val();
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/sdc/get_batch_details',
                type: "GET",
                data: { id:id},
                success:function(data)
                {

                    data=data.split('*');
                    $('#batch_id_'+number).html(data[0]);
                    $('#pack_size_'+number).val(data[1]);
                    $('#description_'+number).val(data[2]);
                    $('#uom_'+number).val(data[2]);

                }
            });
        }
    </script>

    <script>
        function removeDemandsRows(){

            var id=1;

            if (x > 1)
            {
                $('#removeDemandsRows_'+id+'_'+x+'').remove();
                x--;
                $('#count').val(x);
            }
        }


    </script>

    <script>
        function get_ntn()
        {
            var ntn=$('#ntn').val();
            ntn=ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
            $('#buyers_sales').val(ntn[2]);
            sales_tax();
        }
    </script>
    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection