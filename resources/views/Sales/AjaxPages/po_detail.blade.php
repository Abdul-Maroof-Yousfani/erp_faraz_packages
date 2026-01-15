<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

       $data= DB::Connection('mysql2')->table('pos')->where('status',1)->where('id',$id)->first();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail','','1');?>



    </div>
</div>
@if (Request::get('return')==1)
    <?php echo Form::open(array('url' => 'sad/pos_return','id'=>'return_form','class'=>'stop'));?>
@endif
<div class="row printPurchaseRequestVoucherDetail" id="printPurchaseRequestVoucherDetail">


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left">

            {{--<label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo CommonHelper::changeDateFormat($currentDate);?></label>--}}
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <h3 style="text-align: center;">Sales Invoice</h3>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
            {{--< ?php $nameOfDay = date('l', strtotime($currentDate)); ?>--}}
            {{--<label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo '&nbsp;'.$nameOfDay;?></label>--}}

        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="width:49%; float:left;">
            <table  class="table " style="border: solid 1px black;">
                <tbody>

                <?php // $customer_data= CommonHelper::byers_name($sales_order->buyers_id);?>
                <tr>
                    <td class="text-left" style="border:1px solid black;">Customer  Name</td>
                    <td class="text-left" style="border:1px solid black;"><?php echo ucwords($data->customer_name)?></td>

                </tr>
                <tr>
                    <td class="text-left" style="width:60%;border:1px solid black;">Customer Contact NO</td>
                    <td class="text-left" style="width:40%;"><?php echo   ucwords($data->customer_contact_no)  ?></td>

                </tr>
                <tr>
                    <td class="text-left" style="border:1px solid black;width:60%;">Refrence No</td>

                    <td class="text-left" style="width:40%;"><?php echo   $data->ref_no   ?></td>
                </tr>



                </tbody>
            </table>

        </div>
        <div style="width:50%; float:right;">
            <table  class="table " style="border: solid 1px black;">
                <tbody>


                <tr>
                    <td class="text-left" style="border:1px solid black;width:50%;">POS NO.</td>
                    <td class="text-left" style="border:1px solid black;width:50%;"><?php echo strtoupper($data->pos_no)?></td>
                </tr>
                <tr>
                    <td class="text-left" style="border:1px solid black;">POS Date</td>
                    <td class="text-left" style="border:1px solid black;"><?php echo CommonHelper::changeDateFormat(ucwords($data->pos_date));?></td>
                    <?php
                    $pos_no=$data->pos_no;$username=$data->username;
                    $pos_id=$data->id;
                    $pos_date=$data->pos_date
                    ?>
                </tr>





                </tbody>
            </table>
        </div>
    </div>

    <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table  id="tablee" class="table " style="border: solid 1px black;">
                <thead>
                <tr>
                    <th class="text-center" style="border:1px solid black;">S.NO</th>
                    <th class="text-center" style="border:1px solid black;">Item</th>
                    <th class="text-center" style="border:1px solid black;">UOM</th>
                    <th class="text-center" style="border:1px solid black;">Batch Code</th>

                    <th class="text-center" style="border:1px solid black;">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                    <th class="text-center" style="border:1px solid black;">Rate</th>
                    <th class="text-center" style="border:1px solid black;">Amount</th>
                    @if (Request::get('return')==1)<th  class="text-center" style="border:1px solid black;width: 150px">Return QTY</th>

                    @endif

                </tr>
                </thead>
                <?php


                $counter=1;
                $total_amount=0;
                $total_discount=0;
                $total_additional_exp=0;

                $data= DB::Connection('mysql2')->table('pos_data')->where('status',1)->where('master_id',$id)->where('additional_exp',0)->get();
                $additional_exp= DB::Connection('mysql2')->table('pos_data')->where('status',1)->where('master_id',$id)->where('additional_exp',1)->get();
                ?>

                <tbody>
                @foreach($data as  $row)
                    <?php
                    $total_discount+=$row->discount_amount;
                    $return_qty=0;
                    $return_data=SalesHelper::get_return_data_against_pos($row->master_id);
                    if (!empty($return_data->qty)):
                     $return_qty=$return_data->qty;
                    endif;

                    $payment_count=DB::Connection('mysql2')->table('received_paymet')->where('status',1)->where('pos_id',$row->id)->count();
                    ?>
                    <tr>
                        <td style="border:1px solid black;" class="text-center">{{$counter++}}</td>
                        <td style="border:1px solid black;">{{$row->item_des}}</td>
                        <td class="text-center" style="border:1px solid black;">{{strtoupper(CommonHelper::get_uom($row->item_id))}}</td>
                        <td class="text-center" style="border:1px solid black;">{{$row->batch_code}}</td>
                        <td class="text-center" style="border:1px solid black;">{{number_format($row->qty,2)}}</td>
                        <td class="text-center" style="border:1px solid black;">{{number_format($row->rate,2)}}</td>
                        <td class="text-center" style="border:1px solid black;">{{number_format($row->amount,2)}} @php $total_amount+=$row->amount; @endphp</td>
                        <input type="hidden" id="qty{{$counter}}" value="{{$row->qty-$return_qty}}"/>
                        @if (Request::get('return')==1)<td style="border:1px solid black;" class="text-center">
                            <input type="hidden" name="pos_id" value="{{$pos_id}}">
                            <input type="hidden" name="pos_data_id[]" value="{{$row->id}}">
                            <input type="hidden" name="qty[]" value="{{$row->qty}}">
                            <input type="hidden" name="rate[]" value="{{$row->rate}}">
                            <input type="hidden" name="amount[]" value="{{$row->amount}}">
                            <input type="hidden" name="discount_percent[]" value="{{$row->discount_percent}}">
                            <input type="hidden" name="discount_amount[]" value="{{$row->discount_amount}}">
                            <input type="hidden" name="net_amount[]" value="{{$row->net_amount}}">
                            <input type="hidden" name="pos_no" value="{{$row->pos_no}}">
                            <input type="hidden" name="pos_date" value="{{$pos_date}}">
                            <input type="hidden" name="item_id[]" value="{{$row->item_id}}">
                            <input type="hidden" name="batch_code[]" value="{{$row->batch_code}}">
                            <input type="hidden" name="warehouse_id[]" value="{{$row->warehouse_id}}">
                            <input @if($payment_count>0) readonly @endif onkeyup="calc('{{$counter}}')" onblur="calc('{{$counter}}')" type="text" class="form-control return_qtyy" name="return_qty[]" id="return_qty{{$counter}}">

                        @endif

                    </tr>
                @endforeach
                <tr>
                    <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="6">Total</td>
                    <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="1">{{number_format($total_amount,2)}}</td>
                </tr>

                @if ($total_discount>0)
                    <tr>
                        <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="6">Discount Amount</td>
                        <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="1">{{number_format($total_discount,2)}}</td>
                    </tr>
                @endif

                @if (!empty($additional_exp))
                    @foreach($additional_exp as  $row)
                        <tr>
                            <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="6">{{CommonHelper::get_account_name($row->acc_id)}}</td>
                            <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="1">{{number_format($row->amount,2)}} @php $total_additional_exp+=$row->amount @endphp</td>

                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="6">Grand Total</td>
                    <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="1">{{number_format($total_amount+$total_additional_exp-$total_discount,2)}}</td>
                </tr>
                </tbody>


            </table>
            @if (Request::get('return')==1)
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="sf-label">Description</label>
                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                        <textarea required  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
                    </div>
                </div>
                &nbsp;
            </div>
            @endif

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center printHide">
                <button  type="submit" class="btn btn-success btn-sm form_su">Submit</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left printHide">
        <label for="">Show Voucher <input type="checkbox" id="ShowVoucher" onclick="ViewVoucher()"></label>
    </div>
        <?php $tra= DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$pos_no)->where('voucher_type',8)->orderBy('debit_credit',1); ?>

    <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ShowVoucherDetail" id="" style="display: none">
        <div class="table-responsive">
            <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                <thead>
                <tr>
                    <td style="border:1px solid black" colspan="4"><strong><h4>Cost</h4></strong></td>
                </tr>
                <tr>

                    <th style="border:1px solid black" class="text-center">Sr No</th>
                    <th style="border:1px solid black" class="text-center">Account Head<span class="rflabelsteric"></span></th>
                    <th style="border:1px solid black" class="text-center">Debit<span class="rflabelsteric"></span></th>
                    <th style="border:1px solid black" class="text-center">Credit<span class="rflabelsteric"></span></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                $debit_total = 0;
                $credit_total = 0;
                foreach($tra->get() as $row): ?>
                <tr class="text-center">
                    <td style="border:1px solid black;"><?php echo $count++;?></td>
                    <td style="border:1px solid black;">
                        <?php
                            $acc_name = CommonHelper::get_single_row('accounts','id',$row->acc_id);
                        echo $acc_name->name;
                        ?>
                    </td>
                    <td style="border:1px solid black;"><?php if($row->debit_credit == 1): echo number_format($row->amount,2); $debit_total+=$row->amount; endif;?></td>
                    <td style="border:1px solid black;"><?php if($row->debit_credit == 0): echo number_format($row->amount,2); $credit_total+=$row->amount; endif;?></td>
                </tr>
                <?php endforeach;?>
                <tr class="text-center">
                    <td style="border:1px solid black" colspan="2">TOTAL</td>
                    <td style="border:1px solid black"><?php echo number_format($debit_total,2)?></td>
                    <td  style="border:1px solid black"><?php echo number_format($credit_total,2)?></td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>

        <?php $tra= DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$pos_no)->where('voucher_type',11)->orderBy('debit_credit',1); ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ShowVoucherDetail" id="" style="display: none">
            <div class="table-responsive">
                <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                    <thead>
                    <tr>
                        <td style="border:1px solid black" colspan="4"><strong><h4>Voucher</h4></strong></td>
                    </tr>
                    <tr>

                        <th style="border:1px solid black" class="text-center">Sr No</th>
                        <th style="border:1px solid black" class="text-center">Account Head<span class="rflabelsteric"></span></th>
                        <th style="border:1px solid black" class="text-center">Debit<span class="rflabelsteric"></span></th>
                        <th style="border:1px solid black" class="text-center">Credit<span class="rflabelsteric"></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 1;
                    $debit_total = 0;
                    $credit_total = 0;
                    foreach($tra->get() as $row): ?>
                    <tr class="text-center">
                        <td style="border:1px solid black;"><?php echo $count++;?></td>
                        <td style="border:1px solid black;">
                            <?php
                            $acc_name = CommonHelper::get_single_row('accounts','id',$row->acc_id);
                            echo $acc_name->name;
                            ?>
                        </td>
                        <td style="border:1px solid black;"><?php if($row->debit_credit == 1): echo number_format($row->amount,2); $debit_total+=$row->amount; endif;?></td>
                        <td style="border:1px solid black;"><?php if($row->debit_credit == 0): echo number_format($row->amount,2); $credit_total+=$row->amount; endif;?></td>
                    </tr>
                    <?php endforeach;?>
                    <tr class="text-center">
                        <td style="border:1px solid black" colspan="2">TOTAL</td>
                        <td style="border:1px solid black"><?php echo number_format($debit_total,2)?></td>
                        <td  style="border:1px solid black"><?php echo number_format($credit_total,2)?></td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>


    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
        <div class="container-fluid">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="">Prepared By: </h6>
                    <b>   <p><?php echo strtoupper($username);  ?></p></b>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="">Checked By:</h6>
                    <b>   <p><?php  ?></p></b>
                </div>


            </div>
        </div>
    </div>


</div>
@if (Request::get('return')==1)
{{Form::close()}}
@endif
<script>
    function ViewVoucher()
    {
        if($('#ShowVoucher').is(':checked'))
        {
            $('.ShowVoucherDetail').css('display','block');
        }
        else
        {
            $('.ShowVoucherDetail').css('display','none');
        }
    }

    function calc(count)
    {

        var qty=parseFloat($('#qty'+count).val());
        var return_qty=parseFloat($('#return_qty'+count).val());

        if (return_qty >qty)
        {
            alert('QTY Can not Exceed '+qty);
            $('#return_qty'+count).val(0);
        }
    }




    $( document ).ready(function() {
        $("#return_form").submit(function( event ) {
            $('.form_su').prop('disabled', true);
            var des= $('#description_1').val();


            var return_qty=0;
            $('.return_qtyy').each(function (i, obj) {
             var   returnn= $('#'+obj.id).val();
                if (isNaN(returnn))
                {
                    returnn=0;
                }

               return_qty += +returnn;

            });

            if (return_qty==0)
            {
                alert('QTY empty');
                $('.form_su').prop('disabled', false);
                event.preventDefault();
            }

            else
            {
                $('#return_form').submit();
            }

        })

        });
</script>






