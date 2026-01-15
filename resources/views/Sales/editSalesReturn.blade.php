<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client')
{
    $m = $_GET['m'];
}
else
{
    $m = Auth::user()->company_id;
}

?>

<?php $cr_no= SalesHelper::generateCreditNotNo(date('y'),date('m')); ?>
@extends('layouts.default')
@section('content')

    @include('select2')
    @include('number_formate')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">

                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'sad/addCreditNote?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                                </div>
                                            </div>
                                            <?php //echo "<pre>";
                                              //  print_r($CreditNote); die();
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                            <label class="sf-label">Credit No.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input readonly class="form-control" type="text" name="credit_not_no"  value="{{$CreditNote->cr_no}}" />
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                            <label class="sf-label">Credit Date.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input class="form-control" type="date" name="credit_date"  value="{{$CreditNote->cr_date}}" />
                                                        </div>
                                                        <?php $buyers_data=CommonHelper::byers_name($CreditNote->buyer_id);?>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 panel">
                                                            <label class="sf-label">Buyer's Name</label>
                                                            <input readonly class="form-control" type="text" name="customer" id="customer" value="{{$buyers_data->name}}"/>
                                                            <input type="hidden" name="byer_id" value="{{$CreditNote->buyer_id}}">
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
                                                                    <?php $counter=1;

                                                                    ?>

                                                                    @foreach($CreditNoteData as $row)
                                                                        <input type="hidden" name="count[]" value="{{$counter}}">

                                                                        <?php

                                                                        if ($row->type==1):
                                                                            $invoice_data=SalesHelper::get_data_from_invoice($row->voucher_data_id,$row->type);
                                                                                else:
                                                                        $invoice_data=SalesHelper::get_data_from_invoice($CreditNote->so_id,$row->type);
                                                                                endif;

                                                                        $so_id=$invoice_data->so_id;
                                                                        //    die();
                                                                        $return_qty=SalesHelper::return_qty($row->type,$row->voucher_data_id);



                                                                        ?>


                                                                        <div class="">

                                                                            <input type="hidden" name="item_id{{$counter}}" value="{{$row->item}}"/>
                                                                            <input type="hidden" name="invoice_data_id{{$counter}}" value="{{$row->voucher_data_id}}"/>
                                                                            <input type="hidden" name="gi_no{{$counter}}" value="{{$invoice_data->gi_no}}"/>
                                                                            <input type="hidden" name="gi_date{{$counter}}" value="{{$invoice_data->gi_date}}"/>


                                                                            <input type="hidden" id="actual_qty{{$counter}}" value="{{$invoice_data->qty-$return_qty}}" name="actual_qty{{$counter}}">

                                                                            <h5>   <span></span><strong>(<?php echo $counter ?>). GI No. : </strong> {{$invoice_data->gi_no}}
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <strong>GI Date : </strong>{{$invoice_data->gi_date}}
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <strong>QTY. : </strong>{{number_format($invoice_data->qty,2)}}
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <strong>Previous Return QTY. : </strong>{{number_format($return_qty,2)}}
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <strong>Rate : </strong>{{number_format($row->rate,2)}}
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <strong>Amount : </strong>{{number_format($row->amount,2)}}
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
                                                                                <th class="text-center" style="">Discount %</th>
                                                                                <th class="text-center" style="">Discount Amount</th>
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
                                                                                <td><input onkeyup="calc('<?php echo $counter ?>');check_qty(this.id,'{{$counter}}')"  onblur="calc('<?php echo $counter ?>');check_qty(this.id,'{{$counter}})" type="text" class="form-control number zerovalidate" name="qty{{$counter}}" id="qty{{$counter}}"/> </td>
                                                                                <td><input readonly type="text" class="form-control number" name="rate{{$counter}}" value="{{$row->rate}}" id="rate{{$counter}}"/> </td>
                                                                                <td><input readonly type="text" value="<?php echo $row->amount?>" class="form-control number amount" name="amount{{$counter}}" id="amount{{$counter}}"/> </td>


                                                                                <td><input readonly type="text" value="{{$row->discount_percent}}" class="form-control number amount" name="discount_percent{{$counter}}" id="discount_percent{{$counter}}"/></td>
                                                                                <td><input readonly type="text" value="<?php echo $row->discount_amount?>" class="form-control number amount" name="discount_amount{{$counter}}" id="discount_amount{{$counter}}"/></td>
                                                                                <td><input readonly type="text" value="<?php echo $row->net_amount?>" class="form-control number net_amount zerovalidate requiredField" name="net_amount{{$counter}}" id="net_amount{{$counter}}"/></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <input type="hidden"name="warehouse{{$counter}}" id="type" value="{{$invoice_data->warehouse_id}}"/>
                                                                        <?php $counter++; ?>
                                                                    @endforeach
                                                                    <input type="hidden" name="so_id" value="{{$CreditNote->so_id}}"/>
                                                                    <?php

                                                                    $sales_tax=CommonHelper::generic('sales_order',array('id'=>$CreditNote->so_id),'sales_tax')->first();
                                                                    $sales_tax=$sales_tax->sales_tax;

                                                                    ?>
                                                                    <input type="hidden" id="sales_tax_seven" value="{{$sales_tax}}"/>

                                                                    <input type="hidden"name="type" id="type" value="{{$CreditNote->type}}"/>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table style="width: 40%" class="table table-bordered margin-topp table-">
                                                <thead>

                                                </thead>
                                                <tbody>


                                                <tr>
                                                    <td colspan="3">Sales Tax</td>
                                                    <td><input readonly  class="form-control number" type="text" name="sales_tax" id="sales_tax" value=""/> </td>
                                                    <td class="text-center">
                                                        <div class="checkbox">
                                                            <label><input <?php if ($CreditNote->sales_tax>0): ?> checked <?php endif ?>  onclick="appliy('apply17','sales_tax',0)" type="checkbox" id="apply17" value="<?php echo $CreditNote->sales_tax?>">Apply</label>
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
                                                        <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
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

            discount=(total /100)*17;
            $('#discount_amount'+count).val(discount);
            var net=parseFloat(total-discount);
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