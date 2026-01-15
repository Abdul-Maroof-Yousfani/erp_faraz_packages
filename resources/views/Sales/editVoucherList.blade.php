<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    @include('number_formate')
    <style>
        .heading
        {
            font-size: large;
            font-weight: bold;
        }
    </style>

    <h2 style="font-size: large;font-weight: bold; text-decoration: underline;">Bank Receipt Voucher</h2>



    <?php echo Form::open(array('url' => 'fad/addSalesReceipt?m='.$_GET['m'].'','id'=>'createSalesOrder','class'=>'stop'));?>

    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="email">Voucher Date</label>
                    <input type="date" value="{{$NewRvs->rv_date}}" class="form-control" id="v_date" name="v_date">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="pwd">Payment Mode</label>
                    <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                        <option value="1,1" <?php if($NewRvs->pay_mode == 1){echo "selected";}?>>Cheque</option>
                        <option value="2,2" <?php if($NewRvs->pay_mode == 2){echo "selected";}?>>Cash </option>
                        <option value="3,1"<?php if($NewRvs->pay_mode == 3){echo "selected";}?>>Online Transfer </option>
                    </select>
                </div>


                <div  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="pwd"> Banks </label>
                    <?php $bank=DB::Connection('mysql2')->table('bank_detail')->get(); ?>
                    <select name="bank" class="form-control">
                        @foreach($bank as $row)
                            <option value="{{$row->id}}" <?php if($NewRvs->bank == $row->id): echo "selected"; endif;?>>{{$row->bank_name}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="pwd">Cheque No:</label>
                    <input type="text" class="form-control" id="cheque" name="cheque" value="<?php echo $NewRvs->cheque_no?>">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="pwd">Cheque Date:</label>
                    <input value="{{$NewRvs->cheque_date}}" class="form-control" name="cheque_date" type="date" >
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="pwd">Dr Account</label>
                    <select name="acc_id" id="acc_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach(CommonHelper::get_all_account() as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="comment">Remarks:</label>
                    <textarea name="desc" class="form-control" rows="3" id="comment"><?php echo $NewRvs->description?></textarea>

                </div>
            </div>
        </div>
    </div>


    <input type="hidden" name="ref_bill_no" value="" />
    <div>&nbsp;</div>

    <table id="" class="table table-bordered" >


        <thead>
        <tr>
            <th  class="text-center">SI No</th>
            <th  class="text-center">SO No</th>
            <th  class="text-center">Invoice Amount</th>
            <th  class="text-center">Return Amount</th>
            <th  class="text-center">Previous Received Amount</th>
            <th  class="text-center">Received  Amount</th>
            <th  class="text-center">Tax%</th>
            <th  class="text-center">Tax Amount</th>
            <th  class="text-center">Discount Amount</th>
            <th  class="text-center">Net Amount</th>

        </tr>
        </thead>

        <tbody>
        <?php

        $counter=1;
                $TotalTaxAmount = 0;
                $TotalDiscountAmount = 0;
                $TotalNetAmount = 0;
        $gi_no=[];?>
        @foreach($brige_table as $fil)

            <?php
            $TotalTaxAmount+=$fil->tax_amount;
            $TotalDiscountAmount+=$fil->discount_amount;
            $TotalNetAmount+=$fil->net_amount;

            $invoice_detail=SalesHelper::get_sales_detail_for_receipt($fil->si_id);

            $get_freight=SalesHelper::get_freight($fil->si_id);
            $received_amount=$fil->received_amount;//SalesHelper::get_received_payment($fil->si_id);
            $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice($fil->si_id);

            if ($invoice_detail->so_type==1):
                $invoice_amount=$invoice_detail->old_amount;
            else:
                $invoice_amount=$invoice_detail->invoice_amount+$get_freight;
            endif;

            $gi_no[]=$invoice_detail->gi_no;
            ?>
            <input type="hidden" name="si_id[]" value="{{$fil->si_id}}"/>
            <input type="hidden" name="so_id[]" value="{{$invoice_detail->so_id}}"/>

            <tr title="{{'sales_invoice_id='.$fil->si_id}}">
                <td class="text-center">{{strtoupper($invoice_detail->gi_no)}}</td>
                <td class="text-center">@if ($invoice_detail->so_type==1){{$invoice_detail->description}}@else {{strtoupper($invoice_detail->so_no)}}@endif</td>
                <td class="text-center">{{number_format($invoice_amount,2)}}</td>

                <td class="text-center">{{number_format($return_amount,2)}}</td>
                <td class="text-center">{{number_format($received_amount,2)}}</td>


                <td><input class="form-control receive_amount" onkeyup="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
                           onblur="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)" type="text" name="receive_amount[]" id="receive_amount{{$counter}}"
                           value="{{$fil->received_amount}}"
                            ></td>

                <td><select  onchange="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',0)" id="percent{{$counter}}" class="form-control tex_p" name="percent[]">
                        <option value="0">Select</option>
                        @foreach(ReuseableCode::get_invoice_tax() as $row1)
                            <option value="{{$row1->name}}" <?php if($fil->tax_percent == $row1->name):echo "selected"; endif;?>>{{$row1->name}}</option>
                        @endforeach
                    </select>
                </td>


                <td><input onkeyup="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
                           onblur="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)" class="form-control tax" type="text"  value="<?php echo $fil->tax_amount?>" name="tax_amount[]" id="tax_amount{{$counter}}"></td>

                <td><input onkeyup="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
                           onblur="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
                           class="form-control discount" type="text"  value="<?php echo $fil->discount_amount?>" name="discount[]" id="discount_amount{{$counter}}"></td>

                <td><input class="form-control net_amount comma_seprated" type="text" readonly value="{{$invoice_amount-$received_amount-$return_amount}}" name="net_amount[]" id="net_amount{{$counter}}"></td>


            </tr>



            <input type="hidden"  id="inv_amount{{$counter}}" value="{{$invoice_amount}}"/>
            <input type="hidden"  id="rec_amount{{$counter}}" value="{{$received_amount}}"/>
            <input type="hidden"  id="ret_amount{{$counter}}" value="{{$return_amount}}"/>


            <?php $counter++; $gi=implode(',',$gi_no);?>
        @endforeach
        <input type="hidden" name="count" id="count" value="{{$counter-1}}"/>
        <input type="hidden" name="ref_bill_no" value="{{$gi}}" />
        <input type="hidden" name="buyers_id" value="{{$invoice_detail->buyers_id}}"/>
        <tr class="heading" style="background-color: darkgrey">
            <td class="text-center" colspan="7">Total</td>
            <td><input readonly type="text" id="tax_total" class="form-control comma_seprated" value="<?php echo $TotalTaxAmount?>"/></td>

            <td><input readonly type="text" id="discount_total" class="form-control comma_seprated" value="<?php echo $TotalDiscountAmount?>"/></td>
            <td  id=""><input readonly type="text" id="net_total" class="form-control comma_seprated" value="<?php echo $TotalNetAmount?>"/> </td>
        </tr>
        </tbody>
    </table>
    <div class="text-center">
        <button   type="submit" class="btn btn-success">Submit</button>
    </div>
    {{Form::close()}}
    <script>
        $(document).ready(function(){
            hide_unhide();
        });
        function calc(invoice_amount,previous_amount,counter,return_amount,type)
        {

            //  alert(invoice_amount+' '+previous_amount+' '+counter+' '+return_amount);
            var invoice_amount=parseFloat(invoice_amount);
            var previous_amount=parseFloat(previous_amount);
            var return_amount=parseFloat(return_amount);

            if (isNaN(return_amount))
            {
                return_amount=0;
            }

            if (isNaN(previous_amount))
            {
                previous_amount=0;
            }
            var actual_amount=invoice_amount-previous_amount-return_amount;


            var receive_amount=parseFloat($('#receive_amount'+counter).val());

            if (isNaN(receive_amount))
            {
                receive_amount=0;
            }

            if (receive_amount>actual_amount)
            {
                alert('Amount Can not greater them '+actual_amount);
                $('#receive_amount'+counter).val(0);
                return false;
            }

            if (type==0)
            {
                var tax_percent=parseFloat($('#percent'+counter).val());
                var tax_amount=((receive_amount/100)*tax_percent).toFixed(2);
                $('#tax_amount'+counter).val(tax_amount);
            }

            else
            {
                var tax_amount=parseFloat($('#tax_amount'+counter).val());
                if (isNaN(tax_amount))
                {
                    tax_amount=0;
                }
            }



            var discount_amount=parseFloat($('#discount_amount'+counter).val());
            if (isNaN(discount_amount))
            {
                discount_amount=0;
            }

            var net_amount=receive_amount-tax_amount-discount_amount;
            $('#net_amount'+counter).val(net_amount);


            var amount=0;

            $('.net_amount').each(function (i, obj) {

                amount += +$('#'+obj.id).val();
            });
            amount=parseFloat(amount);
            $('#net_total').val(amount);


            var tax=0;

            $('.tax').each(function (i, obj) {

                tax += +$('#'+obj.id).val();
            });
            tax=parseFloat(tax);
            $('#tax_total').val(tax);



            var discount=0;

            $('.discount').each(function (i, obj) {

                discount += +$('#'+obj.id).val();
            });
            discount=parseFloat(discount);
            $('#discount_total').val(discount);

        }

        $(document).ready(function() {
            $('.select2').select2();
            $('.comma_seprated').number(true,2);
        });




        $( "form" ).submit(function( event )
        {
            var validate=validatee();

            if (validate==true)
            {

            }
            else
            {
                return false;
            }

        });
        function validatee()
        {
            var validate=true;
            $( ".receive_amount" ).each(function() {
                var id=this.id;



                var amount=$('#'+id).val();

                if (amount <= 0 || amount=='')
                {
                    $('#'+id).css('border', '3px solid red');

                    validate=false;
                }
                else
                {
                    $('#'+id).css('border', '');

                    if ($('#cheque').val()=='')
                    {
                        $('#cheque').css('border', '3px solid red');

                        validate=false;
                    }

                    if ($('#acc_id').val()=='')
                    {
                        alert('pls select Debit Account');
                        validate=false;
                        return false;


                    }
                }

            });
            return validate;
        }

        $("#percent1").change(function(){
//          var  percent=$('#'+this.id).val();
//           var count=$('#count').val();
//            $('.tex_p').val(percent);
//            for (i=2; i<=count; i++)
//            {
//
//                var inv_amount=$('#inv_amount'+i).val();
//                var rec_amount=$('#rec_amount'+i).val();
//                var ret_amount=$('#ret_amount'+i).val();
//                calc(inv_amount,rec_amount,i,ret_amount);
//            }


        });

        function hide_unhide()
        {
            var pay_mode= $('#pay_mode').val();
            if (pay_mode=='2,2')
            {
                $(".hidee").css("display", "none");
                $('#cheque').val('-');
            }
            else
            {
                $(".hidee").css("display", "block");
            }
        }
    </script>
@endsection