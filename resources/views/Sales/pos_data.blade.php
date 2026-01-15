<?php

use App\Helpers\CommonHelper;
?>

<script>


    $( document ).ready(function() {
        $(".po_dataaaaaa").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            $('.submm').prop('disabled', true);

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(data)
                {
                    $(".submm").css("display", "none");
                    printView('printPurchaseRequestVoucherDetail','',1);

                }
            });

    });

        $('#showDetailModelOneParamerter').on('hidden.bs.modal', function () {

            $('.hidee').prop('disabled', false);
        })
    });
</script>
<?php echo Form::open(array('url' => 'sad/pos_data','id'=>'po_dataaaaa','class'=>'stop po_dataaaaaa'));?>
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
            <h3 style="text-align: center!important;font-size: large!important;font-weight: bold">Sales Invoice</h3>
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
                    <td class="text-left" style="border:1px solid black;"><?php echo ucwords(Request::get('customer_name'))?></td>
                    <input type="hidden" name="customer_namee" value="{{Request::get('customer_name')}}"/>
                    <input type="hidden" name="location_idd" value="{{Request::get('location_id')}}"/>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                </tr>
                <tr>
                    <td class="text-left" style="width:60%;border:1px solid black;">Customer Contact NO</td>
                    <td class="text-left" style="width:40%;"><?php echo  ucwords(Request::get('customer_contact_no'))    ?></td>
                    <input type="hidden" name="customer_contact_noo" value="{{Request::get('customer_contact_no')}}"/>
                </tr>
                <tr>
                    <td class="text-left" style="border:1px solid black;width:60%;">Refrence No</td>
                    <td class="text-left" style="border:1px solid black;width:40%;"><?php echo Request::get('ref_no');?></td>
                    <input type="hidden" name="ref_noo" value="{{Request::get('ref_no')}}"/>
                </tr>



                </tbody>
            </table>

        </div>
        <div style="width:50%; float:right;">
            <table  class="table " style="border: solid 1px black;">
                <tbody>


                <tr>
                    <td class="text-left" style="border:1px solid black;width:50%;">POS NO.</td>
                    <td class="text-left" style="border:1px solid black;width:50%;"><?php echo strtoupper(Request::get('pos_no'));?></td>
                </tr>
                <tr>
                    <td class="text-left" style="border:1px solid black;">POS Date</td>
                    <td class="text-left" style="border:1px solid black;"><?php echo CommonHelper::changeDateFormat(Request::get('pos_date'));?></td>
                    <input type="hidden" name="pos_datee" value="{{Request::get('pos_date')}}"/>
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
                    <th class="text-center" style="border:1px solid black;">Uom</th>
                    <th class="text-center" style="border:1px solid black;">Batch Code</th>
                    <th class="text-center" style="border:1px solid black;">Quantity</th>
                    <th class="text-center" style="border:1px solid black;">Rate</th>
                    <th class="text-center" style="border:1px solid black;">Amount</th>

                </tr>
                </thead>
                <?php

                $sub_ic=Request::get('sub_ic_des');
                $counter=1;
                $total_amount=0;
                $total_discount=0;
                $total_additional_exp=0;

                $additional_exp=Request::get('account_id');
                ?>

                <tbody>
                @foreach($sub_ic as $key => $row)
                    <?php
                    $total_discount+=Request::get('discount_amount')[$key];

                    ?>
                    <tr>
                        <td style="border:1px solid black;" class="text-center">{{$counter++}}</td>

                        <input type="hidden" name="sub_ic_dess[]" value="{{Request::get('sub_ic_des')[$key]}}"/>
                        <input type="hidden" name="item_idd[]" value="{{Request::get('item_id')[$key]}}"/>
                        <input type="hidden" name="batch_codee[]" value="{{Request::get('batch_code')[$key]}}"/>
                        <td style="border:1px solid black;">{{$row}}</td>
                        <td class="text-center" style="border:1px solid black;">{{strtoupper(CommonHelper::get_uom(Request::get('item_id')[$key]))}}</td>
                        <td class="text-center" style="border:1px solid black;">{{Request::get('batch_code')[$key]}}</td>
                        <td class="text-center" style="border:1px solid black;">{{Request::get('actual_qty')[$key]}}</td>
                        <input type="hidden" name="actual_qtyy[]" value="{{Request::get('actual_qty')[$key]}}"/>


                        <td class="text-center" style="border:1px solid black;">{{Request::get('rate')[$key]}}</td>
                        <input type="hidden" name="ratee[]" value="{{Request::get('rate')[$key]}}"/>


                        <td class="text-center" style="border:1px solid black;">{{Request::get('amount')[$key]}} @php $total_amount+=Request::get('amount')[$key]; @endphp</td>
                        <input type="hidden" name="amountt[]" value="{{Request::get('amount')[$key]}}"/>


                        <input type="hidden" name="discount_percentt[]" value="{{Request::get('discount_percent')[$key]}}"/>
                        <input type="hidden" name="discount_amountt[]" value="{{Request::get('discount_amount')[$key]}}"/>
                        <input type="hidden" name="after_dis_amountt[]" value="{{Request::get('after_dis_amount')[$key]}}"/>
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
                    @foreach($additional_exp as $key=> $row)
                        <tr>
                        <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="6">{{CommonHelper::get_account_name($row)}}</td>
                        <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="1">{{number_format(Request::get('expense_amount')[$key],2)}} @php $total_additional_exp+=Request::get('expense_amount')[$key] @endphp</td>

                        <input type="hidden" name="account_idd[]" value="{{$row}}"/>
                        <input type="hidden" name="expense_amountt[]" value="{{Request::get('expense_amount')[$key]}}"/>
                        </tr>
                   @endforeach
                    @endif

                <tr>
                    <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="6">Grand Total</td>
                    <td class="text-center" style="font-size: medium!important;font-weight: bold;border:1px solid black;" colspan="1">{{number_format($total_amount+$total_additional_exp-$total_discount,2)}}</td>
                </tr>
                </tbody>


            </table>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        {{ Form::submit('Submit', ['class' => 'btn btn-success submm']) }}




    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
        <div class="container-fluid">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="">Prepared By: </h6>
                    <b>   <p><?php echo strtoupper(Auth::user()->name);  ?></p></b>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="">Checked By:</h6>
                    <b>   <p><?php  ?></p></b>
                </div>


            </div>
        </div>
    </div>
</div>
<?php echo Form::close();?>







