<?php
$m = $_GET['m'];


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
//if($accType == 'client'){
//   $m = $_GET['m'];
//}else{
//   $m = Auth::user()->company_id;
//}



use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
$InvNo=SalesHelper::get_unique_no_inv(date('y'),date('m'));
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('number_formate')
    <style>
        .bold
        {
            font-size: larger;font-weight: bold
        }
    </style>
    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <span class="subHeadingLabelClass">Create Invoice Form </span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'sad/addInvoiceDetail?m='.$m.'','id'=>'addInvoiceDetail', 'enctype' => 'multipart/form-data'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        {{--<input type="hidden" name="pageType" value="< ?php echo $_GET['pageType']?>">--}}
                        {{--<input type="hidden" name="parentCode" value="< ?php echo $_GET['parentCode']?>">--}}
                        <input type="hidden" name="m" value="<?php echo $_GET['m']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Invoice No</label>
                                                    <input type="text" readonly class="form-control requiredField"  name="inv_no" id="inv_no" value="<?php echo strtoupper($InvNo);?>" placeholder=""/>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Invoice Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <input type="date" class="form-control requiredField"  name="inv_date" id="inv_date" value="<?php echo date('Y-m-d')?>"/>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Client Invoice No</label>
                                                    <input type="text"  class="form-control requiredField"  name="client_ref" id="client_ref" value="" placeholder=""/>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Client Job<span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <input type="text" class="form-control requiredField" placeholder="" name="ship_to" id="ship_to" value="<?php echo CommonHelper::get_client_job_by_id($joborder->client_job)?>" oncanplay="Ship To" />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Client Name<span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <?php $Client = CommonHelper::get_single_row('client','id',$joborder->client_name);?>
                                                    <input type="text" class="form-control" name="client_name" id="client_name" value="<?php echo $Client->client_name?>" readonly>
                                                    <input type="hidden" name="bill_to_client_id" value="<?php echo $joborder->client_name?>">
                                                    <input type="hidden" name="job_order_no" value="<?php echo $joborder->job_order_no?>">
                                                </div>


                                            </div>
                                        </div>


                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <?php $dataBranch = CommonHelper::get_single_row('branch','id',$joborder->branch_id); ?>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Branch Name</label>
                                                    <input type="hidden" name="branch_id" id="branch_id" value="{{$joborder->branch_id}}" class="form-control">
                                                    <input type="text" name="" id="" value="{{$dataBranch->branch_name}}" class="form-control" readonly>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Bill To</label>
                                                    <textarea name="bill_to" class="form-control">{{$Client->client_name}}</textarea>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Ship To</label>
                                                            <textarea name="ship_too" class="form-control">{{$Client->client_name}}
                                                                {{$joborder->address}}</textarea>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label for="">Invoice Description</label>
                                                    <select name="InvDescId" id="InvDescId" class="form-control requiredField">
                                                        <option value="">Select Invoice Desc</option>
                                                        <?php foreach($InvDesc as $Fil):?>
                                                        <option value="<?php echo $Fil['id']?>"><?php echo $Fil['invoice_desc']?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label for="">PO No:</label>
                                                    <input type="text" class="form-control requiredField" name="po_no" id="po_no" value="" >
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    {{--Table LayOut--}}
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><h3>Invoice Detail</h3></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table id="buildyourform" class="table table-bordered">
                                                    <thead>
                                                    <tr>

                                                        <th class="text-center">Item</th>

                                                        <th class="text-center" style="width: 20%;">Description</th>

                                                        <th style="width: 100px;" class="text-center">Qty</th>
                                                        <th style="width: 150px;" class="text-center">Rate</th>
                                                        <th style="width: 150px;" class="text-center">Amount</th>
                                                        <th style="width: 150px;" style="width: 180px;" class="text-center">Sales Tax Rate</th>
                                                        <th style="width: 150px;" class="text-center">Sales Tax Amount</th>
                                                        <th style="width: 150px;" class="text-center">After Tax Amountt</th>
                                                        {{--<th class="text-center">Discount</th>--}}
                                                        {{--<th class="text-center">Net Amount</th>--}}
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php

                                                    $Counter = 1;
                                                    $DisPercent = "";
                                                    $DisAmount = "";
                                                    $SalesTaxPer = "";
                                                    $SalesTaxAmount = "";
                                                    $ReadOnly ="";
                                                    if($joborder->type == 1)
                                                    {
                                                        $Qou = CommonHelper::get_single_row('quotation','id',$joborder->quotation_id);
                                                        $DisPercent = $Qou->discount_percent;
                                                        $DisAmount = $Qou->discount_amount;
                                                        $SalesTaxPer = $Qou->sales_tax_percent;
                                                        $SalesTaxAmount = $Qou->sales_tax_amount;
                                                        $ReadOnly = "readonly";
                                                    }
                                                    $data=[];
                                                    $colour_count=0;
                                                    $branch_id_multiple=0;
                                                    foreach($joborderdata as $row):
                                                    $branch_id_multiple = CommonHelper::get_single_row('job_order','job_order_no',$row->job_order_no);


                                                    if ($colour_count==2):
                                                        $colour_count=0;
                                                    endif;


                                                    if (!in_array($row->job_order_no, $data)):

                                                        $data[]=$row->job_order_no;
                                                        $colour_count++;
                                                    endif;


                                                    $Product = CommonHelper::get_single_row_prodcut('product','product_id',$row->product);
                                                    $UomData = CommonHelper::get_single_row_uom('uom','id',$row->uom_id);
                                                    $Rate = "";
                                                    $Amount = "";
                                                    if($joborder->type ==1)
                                                    {
                                                        $QuoData = CommonHelper::get_single_row('quotation_data','id',$row->quotation_data_id);
                                                        $Rate = $QuoData->rate;
                                                        $Amount = $QuoData->total_cost;
                                                    }

                                                    ?>
                                                    <tr @if($colour_count==1)style="background-color: lightblue" @else style="background-color: lightgrey" @endif class="tex-center">


                                                        <td class="text-center">
                                                            <input type="text" name="item_name<?php echo $Counter?>" id="item_name<?php echo $Counter?>" class="form-control" readonly value="<?php echo $Product->p_name?>">
                                                            <input type="hidden" name="product_id[]" id="product_id<?php echo $Counter?>" value="<?php echo $Product->product_id?>">
                                                        </td>

                                                        <input type="hidden" name="job_order_no[]" value="{{$row->job_order_no}}"/>

                                                        <input type="hidden" name="branch_id_multiple[]" value="<?= $branch_id_multiple->branch_id ?>">


                                                        <td>
                                                            <textarea class="form-control" name="desc[]">{{$row->description}}</textarea>
                                                        </td>

                                                        <input type="hidden" name="uom_id[]" id="uom_id<?php echo $Counter?>" value="<?php echo $UomData->id?>">

                                                        <td>
                                                            <input type="text" onkeyup="calcuate('<?php echo $Counter?>')" name="qty[]" id="qty_<?php echo $Counter?>" class="form-control qty number"  value="<?php echo $row->quantity;?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="rate[]" id="rate_<?php echo $Counter?>" class="form-control rate number requiredField" onkeyup="calcuate('<?php echo $Counter?>')" value="<?php echo  $Rate?>" <?php echo $ReadOnly?>>
                                                        </td>
                                                        <td>
                                                            <input disabled type="text" name="amount[]" id="total_cost_<?php echo $Counter?>" class="form-control cost number requiredField" value="<?php echo $Amount?>" <?php echo $ReadOnly?>>
                                                        </td>

                                                        <td><select onchange="tax_calculate(this.id,'{{$Counter}}')" class="form-control" id="tax_id{{$Counter}}" name="tax_id[]">
                                                                <option value="0*0">No Tax</option>
                                                                @foreach(ReuseableCode::pget_tax() as $row)
                                                                    <option value="{{$row->acc_id.'*'.$row->tax_rate}}">{{$row->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input disabled class="form-control number sales_tax_amount" type="text" id="tax_amount{{$Counter}}" name="tax_amount[]"/> </td>
                                                        <td>  <input class="form-control number after_tax_amount" type="text" id="after_tax_amount{{$Counter}}" name="after_tax_amount[]"/> </td>
                                                        {{--<td>--}}
                                                        {{--<input type="number" name="discount" id="discount" class="form-control">--}}
                                                        {{--</td>--}}
                                                        {{--<td>--}}
                                                        {{--<input type="number" name="net_amount" id="net_amount" class="form-control">--}}
                                                        {{--</td>--}}
                                                    </tr>
                                                    <?php
                                                    $Counter++;
                                                    endforeach;?>
                                                    <tr style="background-color: darkgray">
                                                        <td class="text-center" colspan="2"><b style="font-size: larger;font-weight: bold">Total Cost</b></td>
                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_qty" class="form-control number"/> </td>
                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_rate" class="form-control number"/> </td>
                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_amount" class="form-control number"/> </td>
                                                        <td></td>
                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_sales_tax_amount" class="form-control number"/> </td>
                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_after_tax" class="form-control number"/> </td>

                                                    </tr>
                                                    </tbody>

                                                    <tr>
                                                        <td colspan="2" class="text-center" style="font-family: cursive;font-size: large">Discount</td>
                                                        <td></td>
                                                        <td title="Discount Percentage"><input onkeyup="discount_percentage()" class="form-control" placeholder="" type="text" name="discount_percntage" id="discount_percntage"/> </td>
                                                        <td  title="Dicount Amount Before Tax" style=""><input disabled class="form-control number" placeholder="" type="text" name="discount_amount" id="discount_amount"/> </td>
                                                        <td></td>
                                                        <td title="Discount  Amount Of Tax">
                                                            <input disabled  onkeyup="discount_amount_tax_calculate()" class="form-control number" placeholder="" type="text" name="discount_amount_tax" id="discount_amount_tax"/>
                                                            <label><input checked type="checkbox" name="app_tax" id="app_tax" > Applicable </label>
                                                        </td>

                                                        <td title="Discount Amount After Tax" style=""><input onkeyup="discount_percent_calculate()" class="form-control number" placeholder="" type="text" name="discount_amount_after_tax" id="discount_amount_after_tax"/> </td>

                                                    </tr>


                                                    <tr style="background-color: darkgrey">
                                                        <td colspan="2" class="text-center" style="font-family: cursive;font-size: large">Total Amount After Dicount</td>
                                                        <td></td>
                                                        <td title=""> </td>
                                                        <td  title="" style=""><input disabled class="form-control number bold" placeholder="" type="text" name="total_amount_after_dicount_before_tax" id="total_amount_after_dicount_before_tax"/> </td>
                                                        <td></td>
                                                        <td title="">
                                                            <input disabled  onkeyup="" class="form-control number bold" placeholder="" type="text" name="total_sales_tax_after_tax_dicount" id="total_sales_tax_after_tax_dicount"/>

                                                        </td>

                                                        <td title="" style=""><input onkeyup="" class="form-control number bold" placeholder="" type="text" name="total_amount_after_dicount" id="total_amount_after_dicount"/> </td>

                                                    </tr>


                                                    <tr>
                                                        <td colspan="2" class="text-center" style="font-family: cursive;font-size: large">Advance From Customer</td>
                                                        <td></td>
                                                        <td title="Advance Percentage"><input onkeyup="advance_percentage_calcu()" class="form-control" placeholder="" type="text" name="advance_percntage" id="advance_percntage"/> </td>
                                                        <td  title="Advance Amount Before Tax" style=""><input disabled class="form-control number" placeholder="" type="text" name="advance_amount" id="advance_amount"/> </td>
                                                        <td></td>
                                                        <td title="Advance  Amount Of Tax">
                                                            <input disabled  onkeyup="advance_amount_tax_calculate()" class="form-control number" placeholder="" type="text" name="advance_amount_tax" id="advance_amount_tax"/>
                                                            <label><input checked type="checkbox" name="app_tax" id="advance_app" > Applicable </label>
                                                        </td>

                                                        <td title="Advance Amount After Tax" style=""><input onkeyup="advance_percent_calculate()" class="form-control number" placeholder="" type="text" name="advance_amount_after_tax" id="advance_amount_after_tax"/> </td>

                                                    </tr>


                                                    <tr style="background-color: darkgrey">
                                                        <td colspan="2" class="text-center" style="font-family: cursive;font-size: large">Net Value</td>
                                                        <td></td>
                                                        <td title=""> </td>
                                                        <td  title="" style=""><input disabled class="form-control number bold" placeholder="" type="text" name="net_value_before_tax" id="net_value_before_tax"/> </td>
                                                        <td></td>
                                                        <td title="">
                                                            <input disabled  onkeyup="" class="form-control number bold" placeholder="" type="text" name="net_tax_value" id="net_tax_value"/>

                                                        </td>

                                                        <td title="" style=""><input disabled onkeyup="" class="form-control number bold" placeholder="" type="text" name="net_value" id="net_value"/> </td>

                                                    </tr>

                                                </table>


                                                <label>
                                                    <input  type="checkbox" />Discount Apply</label>
                                                <table class="">

                                                </table>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                    </div>
                                    {{--Table Layout End--}}
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="sf-label">Description<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <textarea name="inv_desc" id="inv_desc" cols="30" rows="3" placeholder="Description" class="form-control" >Note: Sales Tax #1700142799615,Signs Now is Active Tac Payer. NTN # 1427996-7 / CNIC #42201-8184843-3 S.M Shah Jamal Income Tax On Supplies to be deduct under Sesction 153(1)(A) & contactual Service to be deduct under section 153(1)(C)
Sales Tax Charge as per Sales Tax Act
                                        </textarea>
                        </div>

                        <div class="demandsSection"></div>
                        <div>&nbsp;</div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="submit" class="btn btn-success" id="BtnSubmit"">Submit</button>
                            </div>
                        </div>
                        <?php $data=implode(',',$data); ?>
                        <input type="hidden" name="data_id_hidden" value="{{$data}}">
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('.number').number(true,2);
            $('#checkbox1').change(function() {
                if($(this).prop("checked") == true){
                    //alert("Checkbox is checked.");
                    sales_tax_calc();
                }
                else if($(this).prop("checked") == false){
                    //  alert("Checkbox is unchecked.");
                    sales_tax_calc();
                }
            });

            $('#checkbox2').change(function() {
                if($(this).prop("checked") == true){
                    //  alert("Checkbox is checked.");
                    sales_tax_calc();
                }
                else if($(this).prop("checked") == false){
                    //   alert("Checkbox is unchecked.");
                    sales_tax_calc();
                }
            });

            discount_percentage();
            sales_tax_calc();
            total_cost();
            $('.select2').select2();

            $(".btn-success").click(function(e){
                jqueryValidationCustom();
                if(validate == 0){
                    $("input").prop('disabled', false);
                    $('#BtnSubmit').css('display','none');
                    //return false;
                }else{
                    return false;
                }
            });

        });


        function calcuate(number)
        {
            var qty=  parseFloat($('#qty_'+number).val());
            var rate=  parseFloat($('#rate_'+number).val());
            var uom=$('#uom_'+number).val();
//        if (uom==15)
//        {
//            var height=  $('#height_'+number).val();
//            var width=  $('#width_'+number).val();
//            qty=height*width;
//            $('qty_'+number).val(qty);
//
//
//        }
            var total=parseFloat(qty*rate);


            $('#total_cost_'+number).val(parseFloat(total));
            tax_calculate('tax_id'+number,number)
            discount_percent_calc();
            sales_tax_calc();
            total_cost();
        }

        function total_cost()
        {

            // for amount
            var total_cost=0;
            var count=1;
            $('.cost').each(function()
            {
                var cost=parseFloat($(this).val());
                if (isNaN(cost))
                {
                    cost=0;
                }
                total_cost+=cost;

                //count++;
            });
            //alert(total_cost);

            $('#total_amount').val(parseFloat(total_cost));
            var discount_amount=parseFloat($('#discount_amount').val());
            var after_dicount=total_cost-discount_amount;

            $('#total_amount_after_dicount_before_tax').val(after_dicount);

            var advance_amount=parseFloat($('#advance_amount').val());

            if (isNaN(advance_amount))
            {
                advance_amount=0;
            }

            $('#net_value_before_tax').val(after_dicount-advance_amount);

            // for rate


            var total_rate=0;
            var counter_rate=1;
//            $(".CurrencyLoop").each(function()
//            {
//                TotalCurrency += parseFloat($(this).html());
//
//            });
            $('.rate').each(function()
            {
                var rate=parseFloat($(this).val());
                if (isNaN(rate))
                {
                    rate=0;
                }

                total_rate+=rate;;


                //counter_rate++;
            });
            $('#total_rate').val(total_rate);



            // for sales tax amount
            var total_salex_amount=0;
            $('.sales_tax_amount').each(function()
            {
                var sales_tax_amount=parseFloat($(this).val());
                if (isNaN(sales_tax_amount))
                {
                    sales_tax_amount=0;
                }

                total_salex_amount+=sales_tax_amount;

                var discount_amount_tax=parseFloat($('#discount_amount_tax').val());

                if (isNaN(discount_amount_tax))
                {
                    discount_amount_tax=0;
                }
                var after_dicount_tax=total_salex_amount-discount_amount_tax;
                $('#total_sales_tax_after_tax_dicount').val(after_dicount_tax);

                var advance_amount_tax=parseFloat($('#advance_amount_tax').val());

                if (isNaN(advance_amount_tax))
                {
                    advance_amount_tax=0;
                }

                $('#net_tax_value').val(after_dicount_tax-advance_amount_tax);



                //counter_rate++;
            });
            $('#total_sales_tax_amount').val(total_salex_amount);

            var discount_amount_after_dicount=parseFloat($('#discount_amount_after_tax').val());



            if (isNaN(discount_amount_after_dicount))
            {
                discount_amount_after_dicount=0;
            }
            var discount_amount_after_dicount=total_salex_amount-discount_amount_after_dicount;
            $('#total_amount_after_dicount').val(discount_amount_after_dicount);







            // for after Tax
            var total_after_tax=0;
            $('.after_tax_amount').each(function()
            {
                var after_tax_amount=parseFloat($(this).val());
                if (isNaN(after_tax_amount))
                {
                    after_tax_amount=0;
                }

                total_after_tax+=after_tax_amount;
                discount_amount_after_tax


                //counter_rate++;
            });
            $('#total_after_tax').val(total_after_tax);


            var discount_amount_after_tax=parseFloat($('#discount_amount_after_tax').val());

            if (isNaN(discount_amount_after_tax))
            {
                discount_amount_after_tax=0;
            }
            var discount_amount_after_tax=total_after_tax-discount_amount_after_tax;
            $('#total_amount_after_dicount').val(discount_amount_after_tax);

            var advance_amount_after_tax=parseFloat($('#advance_amount_after_tax').val());
            if (isNaN(advance_amount_after_tax))
            {
                advance_amount_after_tax=0;
            }

            $('#net_value').val(discount_amount_after_tax-advance_amount_after_tax);




            // for qty

            var total_qty=0;
            var counter_qty=1;
            $('.qty').each(function()
            {
                total_qty+=parseFloat($(this).val());
                //counter_qty++;
            });

            $('#total_qty').val(total_qty);

            var total_amount=  parseFloat($('#total_amount').val());


            var total_discount=  parseFloat($('#discount_amount').val());
            var advance_from_customer=$('#advance_from_customer').val();
            if (isNaN(total_discount))
            {
                total_discount=0;
            }

            var after_trade_discount=total_amount-total_discount-advance_from_customer;
            $('#discount').val(after_trade_discount);

            var sales_tax_amount=  parseFloat($('#sales_tax_amount').val());

            if (isNaN(sales_tax_amount))
            {
                sales_tax_amount=0;
            }

            var total=parseFloat(total_amount-total_discount);
            total=parseFloat(total-advance_from_customer);
            total=total+sales_tax_amount;

            total=(total).toFixed(2);
            //  alert(total_amount+' '+total_discount+' '+sales_tax_amount);
            $('#net_amount').val(total);

        }


        function discount_calc()
        {
            var total=	parseFloat($('#total_amount').val());
            var discount_percent=parseFloat($('#discount_percent').val());
            if (isNaN(discount_percent))
            {
                discount_percent=0;
            }
            var discount_amount=(total/100)*discount_percent;
            discount_amount=(discount_amount).toFixed(2);
            if(isNaN(discount_amount)){discount_amount =0;}
            $('#discount_amount').val(discount_amount);
            //  var after_discount=total-discount_amount;
            //  $('#discount').val(after_discount);

            total_cost();
            sales_tax_calc();
        }

        function discount_percent_calc()
        {
            var total=	parseFloat($('#total_amount').val());
            var discount_amount=parseFloat($('#discount_amount').val());
            var discount_percent=(discount_amount/total)*100;
            discount_percent=(discount_percent).toFixed(2);
            $('#discount_percent').val(discount_percent);
            total_cost();
            sales_tax_calc();
        }



        function sales_tax_calc()
        {
            if ($('#checkbox1').is(':checked')) {
                var advance_from_customer = parseFloat($('#advance_from_customer').val());
                if (isNaN(advance_from_customer))
                {
                    advance_from_customer=0;
                }
                // alert("1 hogya"+advance_from_customer);
            }else{
                advance_from_customer=0;
            }

            if($('#checkbox2').is(':checked')) {
                var discount_amount = parseFloat($('#discount_amount').val());
                if (isNaN(discount_amount))
                {
                    discount_amount=0;
                }
                //alert("2 hogya"+discount_amount);
            }else{
                discount_amount=0;
            }

            var oktotal=	parseFloat($('#discount').val());
            total = oktotal+advance_from_customer+discount_amount;

            //console.log(total);
            var sales_tax_percent=parseFloat($('#sales_tax_percent').val());
            if(sales_tax_percent > 0)
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
            var sales_tax_amount=(total/100)*sales_tax_percent;
            sales_tax_amount=(sales_tax_amount).toFixed(2);

            $('#sales_tax_amount').val(sales_tax_amount);
            total_cost();
        }

        function sales_tax_percent_calc()
        {
            var total=	parseFloat($('#discount').val());
            var sales_tax_amount_amount=parseFloat($('#sales_tax_amount').val());
            var sales_tax_percent_percent=parseFloat(sales_tax_amount_amount/total)*100;
            var sales_tax_percent_percent=(sales_tax_percent_percent).toFixed(2);
            $('#sales_tax_percent').val(sales_tax_percent_percent);
            total_cost();
        }
        function calculate_height(number)
        {
            var uom= $('#uom_'+number).val();

            if (uom==15)
            {
                var height=  $('#height_'+number).val();
                var width=  $('#width_'+number).val();
                var  qty=height*width;
                $('#qty_'+number).val(qty);


            }

        }



        function tax_calculate(id,counter)
        {
            var percentage=$('#'+id).val();
            percentage=percentage.split('*');
            percentage=parseFloat(percentage[1]);

            var amount=parseFloat($('#total_cost_'+counter).val());

            if (isNaN(amount))
            {
                amount=0;
            }
            var total=(percentage * amount)/100;
            $('#tax_amount'+counter).val(total);
            var after_tax=amount+total;
            $('#after_tax_amount'+counter).val(after_tax);


            total_cost();
            discount_percentage();
        }

        function discount_percentage()
        {

            // for discount amount
            var total=	parseFloat($('#total_amount').val());
            var discount_percent=parseFloat($('#discount_percntage').val());
            if (isNaN(discount_percent))
            {
                discount_percent=0;
            }
            var discount_amount=(total/100)*discount_percent;
            discount_amount=(discount_amount).toFixed(2);
            if(isNaN(discount_amount)){discount_amount =0;}
            $('#discount_amount').val(discount_amount);
            $('#discount_amount_after_tax').val(discount_amount);



            if($('#app_tax').is(':checked'))
            {


                discount_tax_apply();

            }
            total_cost();
        }


        function discount_amount_tax_calculate()
        {
            var discount_amount=parseFloat($('#discount_amount').val());
            var discount_amount_tax=parseFloat($('#discount_amount_tax').val());

            if (isNaN(discount_amount_tax))
            {
                discount_amount_tax=0;
            }



            var total=discount_amount+discount_amount_tax;

            $('#discount_amount_after_tax').val(total);

            total_cost();
        }



        $("#app_tax").change(function()
        {
            if(this.checked)
            {
                // for discount Tax

                var discount_amount=$('#discount_amount').val();
                var total=	parseFloat($('#total_sales_tax_amount').val());
                var discount_percent=parseFloat($('#discount_percntage').val());
                if (isNaN(discount_percent))
                {
                    discount_percent=0;
                }
                var discount_amount1=(total/100)*discount_percent;
                discount_amount1=(discount_amount1).toFixed(2);
                if(isNaN(discount_amount1))
                {
                    discount_amount1 =0;
                }
                $('#discount_amount_tax').val(discount_amount1);
                discount_amount=parseFloat(discount_amount);
                discount_amount1=parseFloat(discount_amount1);
                var total=(discount_amount+discount_amount1);
                $('#discount_amount_after_tax').val(total);
//
            }
            else
            {
                $('#discount_amount_tax').val(0);
                discount_amount_tax_calculate();
            }

            total_cost();
        });

        function discount_tax_apply()
        {
            var discount_amount=$('#discount_amount').val();
            var total=	parseFloat($('#total_sales_tax_amount').val());
            var discount_percent=parseFloat($('#discount_percntage').val());
            if (isNaN(discount_percent))
            {
                discount_percent=0;
            }

            var discount_amount1=(total/100)*discount_percent;
            discount_amount1=(discount_amount1).toFixed(2);
            if(isNaN(discount_amount1))
            {
                discount_amount1 =0;
            }
            $('#discount_amount_tax').val(discount_amount1);
            discount_amount=parseFloat(discount_amount);
            discount_amount1=parseFloat(discount_amount1);
            var total=(discount_amount+discount_amount1);
            $('#discount_amount_after_tax').val(total);
        }


        function discount_percent_calculate()
        {

            var discount_amount=parseFloat($('#discount_amount_after_tax').val());

            var total=	parseFloat($('#total_after_tax').val());

//            if($('#app_tax').is(':checked'))
//            {
//
//
//                var total=	parseFloat($('#total_after_tax').val());
//            }
//            else
//            {
//                var total=	parseFloat($('#total_amount').val());
//                $('#discount_amount').val(discount_amount);
//            }



            var discount_percent=(discount_amount/total)*100;
            discount_percent=(discount_percent).toFixed(5);
            $('#discount_percntage').val(discount_percent);


            // for discount amount before tax
            var total=$('#total_amount').val();
            var discount_amount=(total/100)*discount_percent;
            $('#discount_amount').val(discount_amount);

            //end

            if($('#app_tax').is(':checked'))
            {


                var discount_amount=$('#discount_amount').val();
                var total=	parseFloat($('#total_sales_tax_amount').val());
                var discount_percent=parseFloat($('#discount_percntage').val());
                if (isNaN(discount_percent))
                {
                    discount_percent=0;
                }
                var discount_amount1=(total/100)*discount_percent;
                discount_amount1=(discount_amount1).toFixed(2);
                if(isNaN(discount_amount1))
                {
                    discount_amount1 =0;
                }
                $('#discount_amount_tax').val(discount_amount1);
                discount_amount=parseFloat(discount_amount);
                discount_amount1=parseFloat(discount_amount1);

            }
            total_cost();
            //    sales_tax_calc();
        }




        function advance_percentage_calcu()
        {

            // for discount amount

            var total=	parseFloat($('#total_amount_after_dicount_before_tax').val());
            var advance_percent=parseFloat($('#advance_percntage').val());
            if (isNaN(advance_percent))
            {
                advance_percent=0;
            }
            var advance_amount=(total/100)*advance_percent;
            advance_amount=(advance_amount).toFixed(2);



            if(isNaN(advance_amount))
            {
                advance_amount =0;
            }
            $('#advance_amount').val(advance_amount);
            $('#advance_amount_after_tax').val(advance_amount);


            if($('#advance_app').is(':checked'))
            {


                advance_tax_apply();

            }
            total_cost();
        }





        $("#advance_app").change(function()
        {
            if(this.checked)
            {
                // for discount Tax

                var advance_amount=$('#advance_amount').val();
                var total=	parseFloat($('#total_sales_tax_after_tax_dicount').val());
                if (isNaN(total))
                {
                    total=0;
                }

                var advance_percent=parseFloat($('#advance_percntage').val());
                if (isNaN(advance_percent))
                {
                    advance_percent=0;
                }
                var advance_amount1=(total/100)*advance_percent;

                advance_amount1=(advance_amount1).toFixed(2);
                if(isNaN(advance_amount1))
                {
                    advance_amount1 =0;
                }
                $('#advance_amount_tax').val(advance_amount1);



                advance_amount1=parseFloat(advance_amount1);
                var  advance_amount=parseFloat($('#advance_amount').val());
                var total=(advance_amount+advance_amount1);
                $('#advance_amount_after_tax').val(total);
//
            }
            else
            {
                $('#advance_amount_tax').val(0);
                advance_amount1=parseFloat($('#advance_amount_tax').val());
                var  advance_amount=parseFloat($('#advance_amount').val());
                var total=(advance_amount-advance_amount1);
                $('#advance_amount_after_tax').val(total);
            }

            total_cost();
        });



        function advance_tax_apply()
        {
            var advance_amount=$('#advance_amount').val();
            var total=	parseFloat($('#total_sales_tax_after_tax_dicount').val());
            if (isNaN(total))
            {
                total=0;
            }

            var advance_percent=parseFloat($('#advance_percntage').val());
            if (isNaN(advance_percent))
            {
                advance_percent=0;
            }
            var advance_amount1=(total/100)*advance_percent;

            advance_amount1=(advance_amount1).toFixed(2);
            if(isNaN(advance_amount1))
            {
                advance_amount1 =0;
            }
            $('#advance_amount_tax').val(advance_amount1);



            advance_amount1=parseFloat(advance_amount1);
            var  advance_amount=parseFloat($('#advance_amount').val());
            var total=(advance_amount+advance_amount1);
            $('#advance_amount_after_tax').val(total);
        }

        function advance_percent_calculate()
        {

            var discount_amount=parseFloat($('#advance_amount_after_tax').val());

            var total=	parseFloat($('#total_amount_after_dicount').val());

//            if($('#app_tax').is(':checked'))
//            {
//
//
//                var total=	parseFloat($('#total_after_tax').val());
//            }
//            else
//            {
//                var total=	parseFloat($('#total_amount').val());
//                $('#discount_amount').val(discount_amount);
//            }



            var advance_percent=(discount_amount/total)*100;
            advance_percent=(advance_percent).toFixed(5);
            $('#advance_percntage ').val(advance_percent);


            // for discount amount before tax
            var total=$('#total_amount_after_dicount_before_tax').val();
            var advance_amount=(total/100)*advance_percent;
            $('#advance_amount').val(advance_amount);

            //end

            if($('#advance_app').is(':checked'))
            {


                var advance_amount=$('#advance_amount').val();
                var total=	parseFloat($('#total_sales_tax_after_tax_dicount').val());
                if (isNaN(total))
                {
                    total=0;
                }

                var advance_percent=parseFloat($('#advance_percntage').val());
                if (isNaN(advance_percent))
                {
                    advance_percent=0;
                }
                var advance_amount1=(total/100)*advance_percent;

                advance_amount1=(advance_amount1).toFixed(2);
                if(isNaN(advance_amount1))
                {
                    advance_amount1 =0;
                }
                $('#advance_amount_tax').val(advance_amount1);



                advance_amount1=parseFloat(advance_amount1);
                var  advance_amount=parseFloat($('#advance_amount').val());

            }
            total_cost();
            //    sales_tax_calc();
        }



    </script>




    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection