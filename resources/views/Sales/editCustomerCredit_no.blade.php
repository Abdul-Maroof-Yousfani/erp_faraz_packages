<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client')
{
    $m = session()->get('m');
}
else
{
    $m = Auth::user()->company_id;
}
$type = $credit_note->type;

?>

<?php $cr_no= SalesHelper::generateCreditNotNo(date('y'),date('m')); ?>
@extends('layouts.default')
@section('content')

    @include('select2')
    @include('number_formate')
    <div class="well well_N">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">

                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'sad/updateCreditNote?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="cr_no" value="{{ $credit_note->cr_no }}">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                            <label class="sf-label">Credit No.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input readonly class="form-control" type="text" name="credit_not_no"  value="{{optional($credit_note)->cr_no}}" />
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                            <label class="sf-label">Credit Date.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input class="form-control" type="date" name="credit_date"  value="{{ $credit_note->cr_date ?? date('Y-m-d') }}" />
                                                        </div>                                                        

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                            <label class="sf-label">Buyer's Name</label>
                                                            <input readonly class="form-control" type="text" name="customer" id="customer" value="{{ $credit_note->customer->name }}"/>
                                                            <input type="hidden" name="buyer_id" value="{{$credit_note->customer->id}}">
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Cr Account<span class="rflabelsteric requiredField"><strong>*</strong></span></label>
                                                            <select readonly class="form-control" id="acc_id" name="acc_id">
                                                                <option>Select</option>
                                                                @foreach(CommonHelper::get_accounts_by_parent_code('5-1') as $row)
                                                                    <option @if($row->id==814) selected @endif   value="{{$row->id}}">{{$row->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>


                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="well">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive">
                                                                <?php $counter=1; ?>

                                                                @foreach($credit_note->creditNoteData as $row)
                                                                <input type="hidden" name="count[]" value="{{$counter}}">
                                                                <input type="hidden" name="cr_data_ids[]" value="{{ $row->id }}">

                                                                    <?php


                                                                        $invoice_data=SalesHelper::get_data_from_invoice($row->voucher_data_id,$credit_note->type);
                                                                        // dd($invoice_data, $credit_note->type, $row->so_data_id);
                                                                          $so_id=$invoice_data->so_id;

                                                                            if ($type==1):

                                                                         $return_qty=SalesHelper::return_qty($credit_note->type,$invoice_data->so_data_id,$invoice_data->gd_no);
                                                                   

                                                                            else:
                                                                                $return_qty=SalesHelper::return_qty($credit_note->type,$row->voucher_data_id,$row->voucher_no);                                                                                                                                                             
                                                                            endif;


                                                                        ?>


                                                                    <div class="">

                                                                        <input type="hidden" name="item_id{{$counter}}" value="{{$row->item}}"/>
                                                                        <input type="hidden" name="invoice_data_id{{$counter}}" value="{{$invoice_data->so_data_id}}"/>
                                                                        @if($type==1)



                                                                        @endif
                                                                        <input type="hidden" name="so_data_id{{$counter}}" value="{{$row->so_data_id}}"/>
                                                                        <?php

                                                                        if ($type==1):
                                                                        $bacth=SalesHelper::get_batch_code($row->voucher_data_id,$credit_note->type);
                                                                         else:
                                                                             $bacth=SalesHelper::get_batch_code($invoice_data->so_data_id,$credit_note->type);
                                                                            endif;
                                                                        $bacth=$bacth->batch_code;
                                                                         ?>



                                                                        <input type="hidden" name="batch_code{{$counter}}" value="{{$bacth}}"/>


                                                                        <input type="hidden" name="gi_no{{$counter}}" value="{{$invoice_data->gi_no}}"/>
                                                                        <input type="hidden" name="gi_date{{$counter}}" value="{{$invoice_data->gi_date}}"/>


                                                                        <input type="hidden" id="actual_qty{{$counter}}" value="{{$invoice_data->qty}}" name="actual_qty{{$counter}}">

                                                                        <h5>   <span></span><strong>(<?php echo $counter ?>). GI No. : </strong> {{$invoice_data->gi_no}}
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <strong>GI Date : </strong>{{$invoice_data->gi_date}}
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <strong>QTY. : </strong>{{number_format($invoice_data->qty,2)}}
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <strong>Previous Return QTY. : </strong>{{number_format($return_qty,2)}}
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <strong>Rate : </strong>{{number_format($invoice_data->rate,2)}}
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <strong>Amount : </strong>{{number_format($invoice_data->amount,2)}}
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <strong>SO Data ID : </strong>{{$invoice_data->so_data_id}}
                                                                        </h5>
                                                                    </div>


                                                                    <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center hidden-print">Item</th>

                                                                            <th class="text-center" style="">UOM</th>
                                                                            <th class="text-center" style="">QTY</th>
                                                                            <th class="text-center" style="">Rate</th>
                                                                            <th class="text-center" style="">Amount</th>
                                                                            <th class="text-center" style="">Tax %</th>
                                                                            <th class="text-center" style="">Tax Amount</th>
                                                                            <th class="text-center" style="">Net Amount</th>

                                                                        </tr>
                                                                        </thead>
                                                                         <tbody>
                                                                    <tr>
                                                                        <td class="text-center">{{CommonHelper::get_item_name($row->item)}}</td>


                                                                        <?php $uom_data=CommonHelper::get_subitem_detail($row->item);
                                                                              $uom_data=explode(',',$uom_data);
                                                                               $uom_id=$uom_data[0];
                                                                        ?>
                                                                        <td class="text-center">{{CommonHelper::get_uom_name($uom_id)}}</td>
                                                                        <td><input onkeyup="calc('<?php echo $counter ?>');check_qty(this.id,'{{$counter}}')"  onblur="calc('<?php echo $counter ?>');check_qty(this.id,'{{$counter}})" type="text" class="form-control number zerovalidate" name="itemQty[]" id="qty{{$counter}}" value="{{ $row->qty }}"/> </td>
                                                                        <td><input readonly type="text" class="form-control number" name="rate[]" value="{{$invoice_data->rate}}" id="rate{{$counter}}"/> </td>
                                                                        <td><input readonly type="text" value="{{ $row->amount }}" class="form-control number amount" name="amount[]" id="amount{{$counter}}"/> </td>


                                                                        <td><input readonly type="text" value="{{$invoice_data->tax}}" class="form-control number amount" name="discount_percent[]" id="discount_percent{{$counter}}"/></td>
                                                                        <td><input readonly type="text" value="{{$invoice_data->tax_amount}}" class="form-control number amount" name="discount_amount[]" id="discount_amount{{$counter}}"/></td>
                                                                        <td><input readonly type="text" value="{{ $row->net_amount }}" class="form-control number net_amount zerovalidate requiredField" name="net_amount[]" id="net_amount{{$counter}}"/></td>
                                                                    </tr>
                                                                         </tbody>
                                                                    </table>
                                                                    <input type="hidden"name="warehouse{{$counter}}" id="type" value="{{$invoice_data->warehouse_id}}"/>
                                                                    <?php $counter++; ?>
                                                                    @endforeach
                                                                    <input type="hidden" name="so_id" value="{{$so_id}}"/>
                                        <?php

                                        $sales_tax=CommonHelper::generic('sales_order',array('id'=>$so_id),'sales_tax')->first();
                                        $sales_tax=$sales_tax->sales_tax;

                                        ?>
                                                                    <input type="hidden" id="sales_tax_seven" value="{{$sales_tax}}"/>

                                                                    <input type="hidden"name="type" id="type" value="{{$credit_note->type}}"/>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table style="width: 40%" class="hide table table-bordered margin-topp table-">
                                                <thead>

                                                </thead>
                                                <tbody>


                                                <tr>
                                                    <td colspan="3">Sales Tax</td>
                                                    <td><input readonly  class="form-control number" type="text" name="sales_tax" id="sales_tax" value=""/> </td>
                                                    <td class="text-center">
                                                        <div class="checkbox">
                                                            <label><input <?php if ($sales_tax>0): ?> checked <?php endif ?>  onclick="appliy('apply17','sales_tax',0)" type="checkbox" id="apply17" value="">Apply</label>
                                                        </div> </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="2">Further Sales Tax @3%</td>
                                                    <td><td><input readonly  class="form-control number" type="text" name="sales_tax_further" id="sales_tax_further" value=""/> </td></td>
                                                    <td class="text-center"> <div class="checkbox">
                                                            <label><input onclick="appliy('applyfurther','sales_tax_further',1)" type="checkbox" id="applyfurther" value="">Apply</label>
                                                        </div> </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="2">Total Sales Tax</td>
                                                    <td><td><input style="font-weight: bold;font-size: x-large" readonly class="form-control number" type="text" name="sales_total" id="sales_total" value="0"/> </td></td>
                                                    <td></td>

                                                </tr>

                                                <tr>
                                                    <td colspan="2">Grand Total</td>
                                                    <td><td><input style="font-weight: bold;font-size: x-large" readonly class="form-control number" type="text" name="grand_total" id="grand_total" value="0"/> </td></td>
                                                    <td></td>

                                                </tr>





                                                </tbody>
                                                </tr>
                                            </table>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="sf-label">Description</label>
                                                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                        <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField">{{ $credit_note->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pvsSection"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success submit']) }}
                                                <!--
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

										<input type="button" class="btn btn-sm btn-primary addMorePvs" value="Add More PV's Section" />
										<!-->
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
    <?php

    ?>

<script>
    $(document).ready(function()
    {
        $('.number').number(true,2);


    });

    function calc(count)
    {
      var qty=  parseFloat($('#qty'+count).val());
      var rate=  parseFloat($('#rate'+count).val());
      var total=qty*rate;
      $('#amount'+count).val(total);

     var discount =parseFloat($('#discount_percent'+count).val());


        if (isNaN(discount))
        {
            discount=0;
        }

        discount=(total /100)*discount;
        $('#discount_amount'+count).val(discount);
        var net=parseFloat(total+discount);
        $('#net_amount'+count).val(net);

      appliy('applyfurther','sales_tax_further',1);
        appliy('apply17','sales_tax',0);
    }

    function appliy(checl,id,type)
    {


            if ($('#'+checl).is(':checked'))
            {
                var net_amount=0;
                $('.net_amount').each(function (i, obj)
                {
                    var id=(obj.id);
                    net_amount += +$('#'+id).val();
                });
                var salesTax=	parseFloat(net_amount);
                if (type==0)
                {
                    sales_tax=(salesTax/100)*17;
                    $('#'+id).val(sales_tax);
                }
                else
                {
                    sales_tax=(salesTax/100)*3;
                    $('#'+id).val(sales_tax);
                }

            }
            else
            {
                $('#'+id).val(0);
            }
      var sales_taxx=  parseFloat($('#sales_tax').val());
     var furtherr=  parseFloat($('#sales_tax_further').val());
        var total=sales_taxx+furtherr;
        $('#sales_total').val(total);

        var net=parseFloat(net_amount+total);
        $('#grand_total').val(net);


    }

    function check_qty(id,number)
    {
        var qty=parseFloat($('#'+id).val());
        var sales_qty=parseFloat($('#actual_qty'+number).val());

        if (qty>sales_qty)
        {
            alert('Returned QTY can not greater than actual qty');
            $('#'+id).val(0);
            $('#amount'+number).val(0);
            $('#discount_amount'+number).val(0);
            $('#net_amount'+number).val(0);
            appliy('applyfurther','sales_tax_further',1);
            appliy('apply17','sales_tax',0);

        }
        else
        {
            var rate = parseFloat( $('#rate'+number).val());
            var total=(qty*rate).toFixed(2)
            $('#amount'+number).val(total);

        }
    }
    $(document).ready(function() {
    $(".btn-success").click(function(e){

        //alert();
        var purchaseRequest = new Array();
        var val;
        //$("input[name='demandsSection[]']").each(function(){
        purchaseRequest.push($(this).val());
        //});
        var _token = $("input[name='_token']").val();
        for (val of purchaseRequest) {
            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });
    });
</script>
@endsection