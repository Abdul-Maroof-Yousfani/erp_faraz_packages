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

    <?php

  //  $WhereIn = implode(',',$val);
  //  $Colll = DB::Connection('mysql2')->select('select gi_no,buyers_id from sales_tax_invoice where id in('.$WhereIn.') group by buyers_id');

    ?>

    <?php echo Form::open(array('url' => 'fad/pos_payment','id'=>'createSalesOrder','class'=>'stop'));?>

    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="email">Voucher Date</label>
                    <input type="date" value="{{date('Y-m-d')}}" class="form-control" id="v_date" name="v_date">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="pwd">Payment Mode</label>
                    <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                        <option value="1,1">Cheque</option>
                        <option value="2,2">Cash </option>
                        <option value="3,1">Online Transfer </option>
                    </select>
                </div>


                <div  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="pwd"> Banks </label>
                    <?php $bank=DB::Connection('mysql2')->table('bank_detail')->get(); ?>
                    <select name="bank" class="form-control">
                        @foreach($bank as $row)
                            <option value="{{$row->id}}">{{$row->bank_name}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="pwd">Cheque No:</label>
                    <input type="text" class="form-control" id="cheque" name="cheque">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="pwd">Cheque Date:</label>
                    <input value="{{date('Y-m-d')}}" class="form-control" name="cheque_date" type="date" >
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
                    <textarea name="desc" class="form-control" rows="3" id="comment"><?php ?></textarea>

                </div>
            </div>
        </div>
    </div>



    <div>&nbsp;</div>

    <table id="" class="table table-bordered" >


        <thead>
        <tr>

            <th  class="text-center">POS No</th>
            <th  class="text-center">Invoice Amount</th>
            <th  class="text-center">Return Amount</th>
            <th  class="text-center">Previous Received Amount</th>
            <th  class="text-center">Received  Amount</th>
            <th  class="text-center">Discount Amount</th>
            <th  class="text-center">Net Amount</th>

        </tr>
        </thead>

        <tbody>
        <?php $counter=1;
        $gi_no=[];?>
        @foreach($val as $row)

            <?php

            $pos_data=SalesHelper::get_pos_detail($row);
            $rece=SalesHelper::get_received_payment_for_pos($row);
            $return_data=SalesHelper::get_return_data_against_pos($row);
            $pos_no[]=$pos_data->pos_no;
            $return_amount=0;
            if (!empty($return_data->net_amount)):
            $return_amount=$return_data->net_amount;
            endif;
            $received_amount=0;
            ?>
            <input type="hidden" name="pos_id[]" value="{{$row}}"/>


               <tr title="{{'sales_invoice_id='.$row}}">
                <td class="text-center">{{strtoupper($pos_data->pos_no)}}</td>
                <td class="text-center">{{number_format($pos_data->amount,2)}}</td>
                <td class="text-center">{{number_format($return_amount,2)}}</td>
                <td class="text-center receive_amount">{{number_format($rece,2)}}</td>
                <td><input
                           onblur="calc('{{$pos_data->amount}}','{{$rece}}','{{$counter}}','{{$return_amount}}','{{1993}}')"
                           onkeyup="calc('{{$pos_data->amount}}','{{$rece}}','{{$counter}}','{{$return_amount}}','{{1993}}')"
                           class="form-control" type="text" value="{{$pos_data->amount-$return_amount-$rece}}" name="receive_amount[]" id="receive_amount{{$counter}}" /> </td>
               <td><input
                           onblur="calc('{{$pos_data->amount}}','{{$rece}}','{{$counter}}','{{$return_amount}}','{{1993}}')"
                           onkeyup="calc('{{$pos_data->amount}}','{{$rece}}','{{$counter}}','{{$return_amount}}','{{1993}}')"
                           class="form-control discount" type="text" value="" name="discount_amount[]" id="discount_amount{{$counter}}" /> </td>
               <td><input class="form-control net_amount comma_seprated" type="text" value="" name="net_amount[]" id="net_amount{{$counter}}" /> </td>
               </tr>



            <?php $counter++; $gi=implode(',',$gi_no);?>  @endforeach

        <tr class="heading" style="background-color: darkgrey">
            <td class="text-center" colspan="4">Total</td>
            <td><input readonly type="text" id="tax_total" class="form-control comma_seprated"/></td>

            <td><input readonly type="text" id="discount_total" class="form-control comma_seprated"/></td>
            <td  id=""><input readonly type="text" id="net_total" class="form-control comma_seprated"/> </td>
        </tr>

        </tbody>
    </table>

    <?php $pos_no=implode(",",$pos_no); ?>
    <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
    <input type="hidden" name="ref_bill_no" value="{{$pos_no}}" />
    <div class="text-center">
        <button   type="submit" class="btn btn-success" onclick="SetValue(0)">Submit</button>
        <button type="submit" id="BtnSaveAndPrint" class="btn btn-info BtnSaveAndPrint" onclick="SetValue(1)">Save & Print</button>
    </div>
    {{Form::close()}}
    <script>

        function SetValue(v)
        {
            $('#SavePrintVal').val(v);
        }
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