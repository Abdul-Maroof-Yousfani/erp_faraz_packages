<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;


?>
<button onclick="change_formate()" type="button" class="btn btn-primary btn-xs">Format</button>
<button onclick="change()" type="button" class="btn btn-primary btn-xs">Show PKR</button>
@if($purchase_voucher->pv_status==1)
    <div style="text-align: right">
        <button onclick="approve_purchase({{$purchase_voucher->id}})" type="button" class="btn btn-success btn-xs">Approve</button>
    </div>
@endif
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">





    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                        echo ' '.'('.date('D', strtotime($x)).')';?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <img src="{{URL('assets/img/Gudia-Logo2.png')}}" />
                        </div>
                        <br />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php echo '(Purchase Voucher)' // PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status);?>
                        </div>
                    </div>
                </div>

            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:100%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">

                            <thead>
                            <tr>
                                <th class="text-center" style="width:50px;">PV No</th>
                                <th class="text-center">PV Date</th>
                                <th class="text-center">GRN No</th>
                                <th class="text-center" >Bill No / Ref No</th>
                                <th class="text-center">Bill / Ref Date</th>
                                <th  class="text-center" >Due Date.</th>
                                <th class="text-center">Purchase Type.</th>
                                <th  class="text-center">Vendor</th>
                                <th  class="text-center">Sales Tax Acc.</th>
                                <th  class="text-center">Sales Tax Amount</th>
                                <th  class="text-center">Currency </th>



                                <?php ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center" style=""><?php  echo $purchase_voucher->pv_no;?></td>
                                <td class="text-center" style=""><?php  echo  strtoupper($purchase_voucher->grn_no);?></td>
                                <td class="text-center" style=""><?php
                                    $date = $purchase_voucher->pv_date;
                                    $day=   date('D', strtotime($date));

                                    echo CommonHelper::changeDateFormat($purchase_voucher->pv_date).' '.'('.$day.')';?></td>
                                <td class="text-center" style=""><?php  echo $purchase_voucher->slip_no;?></td>
                                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($purchase_voucher->bill_date);?></td>
                                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($purchase_voucher->due_date);?></td>>
                                <td class="text-center"><?php  echo CommonHelper::get_purchase_type_name($purchase_voucher->purchase_type);?></td>
                                <td class="text-center"><?php  echo CommonHelper::get_supplier_name($purchase_voucher->supplier);?></td>


                                <td class="text-center"><?php echo  CommonHelper::get_account_name($purchase_voucher->sales_tax); ?></td>


                                <td class="text-right"><?php echo number_format($purchase_voucher->sales_tax_amount,2);?></td>




                                <td class="text-center"><?php      $currency=CommonHelper::get_curreny_name($purchase_voucher->currency)?>
                                    @if($currency=='')

                                        <?php echo  'PKR';
                                        $currency_name='PKR'.'---1.00';
                                        ?>
                                    @else

                                     <?php    $currency_name=$currency ?>
                                        {{$currency.'---'.$purchase_voucher->currency_rate}}
                                    @endif

                                </td>
                            </tr>



                            </tbody>
                        </table>
                @if($purchase_voucher->sales_tax!=0)
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <b>Department {{ CommonHelper::get_account_name($purchase_voucher->sales_tax)}})</b>
                            @foreach(CommonHelper::sales_tax_allocation_data($purchase_voucher->id,0) as $row2)

                                @if($row2->dept_id!='0')
                                    <div>
                                        <div style="font-size:10px; " class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            {{CommonHelper::get_dept_name($row2->dept_id)}}
                                        </div>
                                        <div style="font-size:13px " class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            {{number_format($row2->percent)}}
                                        </div>
                                        <div style="font-size:13px; " class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            {{number_format($row2->amount,2)}}
                                        </div>
                                    </div>

                                @endif

                            @endforeach

                        </div>
@endif


                    </div>

                </div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <?php    $total_net_amount=0; ?>
                        @foreach ($PurchaseVoucherData as $row)
                            <table id="detail"  class="table table-bordered table-striped table-condensed tableMargin detail">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width:50px;">S.No</th>
                                    <th class="text-center">Account Name</th>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">UOM</th>
                                    <th  class="text-center" style="width:100px;">Qty.</th>
                                    <th class="text-center" style="width:100px;">Rate.</th>
                                    <th  class="text-center">Amount</th>
                                    <th  class="text-center">Discount Percent</th>
                                    <th  class="text-center">Discount Amount</th>
                                    <th  class="text-center">Total Amount</th>
                                    <th   class="text-center showw" style="display: none">Amount In PKR</th>


                                    <?php ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $total_qty=0;
                                $total_rate=0;
                                $total_amount=0;
                                $total_sales_tax_per=0;
                                $total_sales_tax_amount=0;


                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td><?php echo CommonHelper::get_account_name($row->category_id);?> </td>
                                    <td> <?php echo CommonHelper::get_item_name($row->sub_item);?></td>
                                    <td> <?php echo CommonHelper::get_uom_name($row->uom);?></td>
                                    <td class="text-center"><?php echo $row->qty; $total_qty+=$row->qty;?></td>
                                    <td class="text-center"><?php echo number_format($row->rate,2);$total_rate+=$row->rate; ?></td>
                                    <td class="text-right"><?php echo number_format($row->amount,2);$total_amount+=$row->amount; ?></td>
                                    <td class="text-right"><?php echo number_format($row->discount_percent,2);//$total_amount+=$row->amount; ?></td>
                                    <td class="text-right"><?php echo number_format($row->discount_amount,2);//$total_amount+=$row->amount; ?></td>
                                    <td class="text-right"><?php echo number_format($row->total_amount,2);$total_amount+=$row->amount; ?></td>
                                    <td style="display: none"  class="text-right showw"><?php echo number_format($row->amount*$purchase_voucher->currency_rate,2);?></td>




                                </tr>


                                <?php $master_id[]=$row->id;


                                ?>


                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 detail">

                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <b>Department({{CommonHelper::get_account_name($row->category_id)}})</b>
                                            @foreach(CommonHelper::department_allocation_data($row->id,1) as $row1)





                                                @if($row1->dept_id!='0')
                                                    <div>
                                                        <div style="font-size:10px; " class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            {{CommonHelper::get_dept_name(ucfirst($row1->dept_id))}}
                                                        </div>
                                                        <div style="font-size:13px; "  class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                            {{number_format($row1->percent)}}
                                                        </div>
                                                        <div style="font-size:13px; "  class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                            {{number_format($row1->amount,2)}}
                                                        </div>
                                                    </div>
                                                @endif

                                            @endforeach

                                        </div>


                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <b>Cost Center({{CommonHelper::get_account_name(ucfirst($row->category_id))}})</b>
                                            @foreach(CommonHelper::cost_center_allocation_data($row->id,1) as $row2)

                                                @if($row2->dept_id!='0')
                                                    <div>
                                                        <div style="font-size:10px; " class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            {{CommonHelper::get_cost_name($row2->dept_id)}}
                                                        </div>
                                                        <div style="font-size:13px " class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                            {{number_format($row2->percent)}}
                                                        </div>
                                                        <div style="font-size:13px; " class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                            {{number_format($row2->amount,2)}}
                                                        </div>
                                                    </div>

                                                @endif

                                            @endforeach

                                        </div>


                                        

                                    </div></div>     </div>

                            @endforeach

                                    <!--
                        <tr>
                            <td  style="background-color: darkgray" colspan="4">TOTAL</td>
                            <td  style="background-color: darkgray" class="text-center" colspan="1"></td>
                            <td  style="background-color: darkgray" class="text-center" colspan="1"></td>
                            <td  style="background-color: darkgray" class="text-right" colspan="1"></td>
                            <td  style="background-color: darkgray" class="text-center" colspan="1"></td>
                            <td  style="background-color: darkgray" class="text-right" colspan="1"></td>
                            <td  style="background-color: darkgray" class="text-right" colspan="1"></td>
                            <td  style="background-color: darkgray" class="text-right" colspan="1"></td>

                        </tr>
<!-->

                            <input type="hidden" id="d_t_amount" value="{{number_format($total_net_amount,2)}}">
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">

                            <table style="display: none;" id="short"   class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width:50px;">S.No</th>
                                    <th style="width:500px;" class="text-center">Account</th>
                                    <th class="text-center" style="width:150px;">Debit</th>
                                    <th class="text-center" style="width:150px;">Credit</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $counter = 1;
                                $g_t_debit = 0;
                                $g_t_credit = 0;
                                foreach ($PurchaseVoucherData as $row2)
                                {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td><?php  echo CommonHelper::get_account_name($row2->category_id);;?></td>
                                    <td class="debit_amount text-right">
                                        <?php

                                        echo number_format($row2->amount,2);
                                        $g_t_debit+=$row2->amount;

                                        ?>
                                    </td>
                                    <td class="credit_amount text-right"></td>


                                </tr>
                                @if($purchase_voucher->sales_tax!=0 && $counter==2)
                                    <tr>

                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td><?php  echo CommonHelper::get_account_name($purchase_voucher->sales_tax);?></td>
                                        <td class="debit_amount text-right">
                                            <?php

                                            echo number_format($purchase_voucher->sales_tax_amount,2);
                                            $g_t_debit+=$row2->sales_tax_amount;

                                            ?>
                                        </td>
                                        <td class="credit_amount text-right"></td>

                                    </tr>
                                @endif
                                <?php
                                }
                                ?>

                                <tr>

                                    <td class="text-center"><?php echo $counter;?></td>
                                    <td><?php   echo CommonHelper::get_supplier_name($purchase_voucher->supplier);?></td>
                                    <td class="debit_amount text-right">

                                    </td>
                                    <td class="credit_amount text-right">

                                        <?php

                                        echo number_format($g_t_debit+$purchase_voucher->sales_tax_amount,2);
                                        $g_t_credit+=$purchase_voucher->total_net_amount;

                                        ?>
                                    </td>

                                </tr>


                                <tr class="sf-table-total">
                                    <td colspan="2">
                                        <label for="field-1" class="sf-label"><b>Total</b></label>
                                    </td>
                                    <td class="text-right"><b><?php echo number_format($g_t_debit+$purchase_voucher->sales_tax_amount,2);?></b></td>
                                    <td class="text-right"><b><?php echo number_format($g_t_debit+$purchase_voucher->sales_tax_amount,2);?></b></td>
                                    <td colspan="1"></td>
                                </tr>
                                </tbody>
                            </table>

                        </div></div>

                    <input type="hidden" name="d_t_amount_1" id="d_t_amount_1" value="{{$g_t_debit+$purchase_voucher->sales_tax_amount}}"/>
            <tr style="text-transform: capitalize;"><td>Amount In Words :  <?php  echo $purchase_voucher->amount_in_words.' '.'('.$currency_name.')' //echo number_format($total_net_amount,2)  ?></td></tr>

                 
                    </table>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h6>Description: <?php echo strtoupper($purchase_voucher->description); ?></h6>
                            </div>
                        </div>
                        <style>
                            .signature_bor {
                                border-top:solid 1px #CCC;
                                padding-top:7px;
                            }
                        </style>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                            <div class="container-fluid">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Prepared By: </h6>
                                        <b>   <p><?php //echo strtoupper($username);  ?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Checked By:</h6>
                                        <b>   <p><?php  ?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b>  <p></p></b>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        <?php ?>



    </div>


    <script>
        function change_formate()

        {
            if(!$('.detail').is(':visible'))
            {
                $(".detail").css("display", "block");
                $("#short").css("display", "none");
            }
            else
            {
                $(".detail").css("display", "none");
                $("#short").css("display", "block");
            }

        }



        function change()

        {


            if(!$('.showw').is(':visible'))
            {
                $(".showw").css("display", "block");

            }
            else
            {
                $(".showw").css("display", "none");

            }

        }

        function approve_purchase(id)
        {


            $.ajax({
                url: '/pdc/approve_purchase',
                type: 'Get',
                data: {id: id},

                success: function (response)
                {

                    //  $('#data').html(response);
                    $('.'+id).html('Approve');
                    $('#'+id).css("background-color","yellow");
                    $('.'+id).focus();
                    $("[data-dismiss=modal]").trigger({ type: "click" });


                }
            });


        }

    </script>


