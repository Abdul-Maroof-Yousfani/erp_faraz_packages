<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;

//$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>


<style>
    textarea {
        border-style: none;
        border-color: Transparent;

    }
    @media print{
        .printHide{
            display:none !important;
        }
        .fa {
            font-size: small;!important;
        }

        .table-bordered{
            border: 1px solid black;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid black !important;
        }
    }
    table{
        border: solid 1px black;
    }
    tr{
        border: solid 1px black;
    }
    td{
        border: solid 1px black;
    }
    th{
        border: solid 1px black;
    }


</style>
<?php

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printPurchaseRequestVoucherDetail">
    <div class="">
        <!--
        < ?php  StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m,$sales_order->purchase_request_status,$sales_order->status,$row->id,'purchase_request_no','purchase_request_status','status','purchase_request','purchase_request_data');?>
    </div>
    <!-->
        <div style="line-height:5px;">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
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
                        <h3 style="text-align: center;">Delivery Note</h3>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                        {{--< ?php $nameOfDay = date('l', strtotime($currentDate)); ?>--}}
                        {{--<label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo '&nbsp;'.$nameOfDay;?></label>--}}

                    </div>
                </div>


                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="width:49%; float:left;">
                            <table  class="table " style="border: solid 1px  black">
                                <tbody>
                                <?php $customer_data= CommonHelper::byers_name($delivery_note->buyers_id);?>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">Buyer's Name</td>
                                    <td class="text-left" style="border: solid 1px black;"><?php echo ucwords($customer_data->name)?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Buyer's Order NO</td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;"><?php echo $delivery_note->order_no.' ';    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Buyer's Order Date</td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;"><?php echo CommonHelper::changeDateFormat($delivery_note->order_date);?></td>
                                </tr>
                                <tr>
                                    <?php $SalesOrder = CommonHelper::get_single_row('sales_order','id',$delivery_note->master_id);?>
                                    <td class="text-left" style="width:60%;">Buyer's Unit</td>
                                    <td class="text-left" style="width:40%;"><?php echo $SalesOrder->buyers_unit;?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">Buyer's Address</td>
                                    <td style="border: solid 1px black;font-size: xx-small" class="text-left"><?php echo  ucwords($customer_data->address);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Destination</td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;"><?php echo $delivery_note->destination;?></td>
                                </tr>


                                </tbody>
                            </table>

                        </div>

                        <div style="width:50%; float:right;">
                            <table  class="table " style="border: solid 1px black; border: solid 1px black;">
                                <tbody>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:50%;">DN NO.</td>
                                    <td class="text-left" style="border: solid 1px black;width:50%;"><?php echo strtoupper($delivery_note->gd_no);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">DN Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($delivery_note->gd_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:50%;">SO NO.</td>
                                    <td class="text-left" style="border: solid 1px black;width:50%;"><?php echo strtoupper($delivery_note->so_no);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">SO Date</td>
                                    <td class="text-left" style="border: solid 1px black;"><?php echo CommonHelper::changeDateFormat($delivery_note->so_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">Other Reference(S)</td>
                                    <td class="text-left" style="border: solid 1px black;"><?php echo $delivery_note->other_refrence?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Terms Of Delivery</td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;"><?php echo $delivery_note->terms_of_delivery;?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="text-align: left" class="printHide">
                        <label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />Printable Format </label>
                        <label class="text-left"><input type="checkbox" onclick="show_hide2()" id="formats2" />Bundle Printable Format </label>
                    </div>



                    <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table " style="border: solid 1px black;">
                                <thead>
                                <tr>
                                    <th class="text-center" style="border: solid 1px black;">S.NO</th>
                                    <th class="text-center" style="border: solid 1px black;">Item</th>

                                    <th class="text-center" style="border: solid 1px black;">Uom</th>




                                    <th class="text-center" style="border: solid 1px black;">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>

                                    <th class="text-center td bundleHide" style="border: solid 1px black;">Rate</th>
                                    <th class="text-center Amoun bundleHide" style="border: solid 1px black;">Amount</th>
                                    <th class="text-center Amoun bundleHide" style="border: solid 1px black;">Discount %</th>
                                    <th class="text-center Amoun bundleHide" style="border: solid 1px black;">Discount Amount</th>
                                    <th class="text-center Amoun bundleHide" style="border: solid 1px black;">Net Amount</th>
                                    {{--<th class="text-center" style="border: solid 1px black;">Net Amount</th>--}}


                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $AttchCount = 0;
                                $counter=1;
                                $total=0;
                                $tttot = 0;
                                $total_amount=0;
                                $total_discount =0;
                                $working_counter =0;
                                foreach ($delivery_note_data as $row1){

                                if ($row1->bundles_id==0):
                                $total_amount+=$row1->amount;
                                $total_discount+=$row1->discount_amount;
                                ?>



                                <tr id="{{$row1->id}}" ondblclick="diss(this.id)" title="">
                                    <td class="text-center" style="border: solid 1px black;"><?php echo $counter++;?></td>
                                    <td class="text-left" style="border: solid 1px black;">


                                        <?php echo $row1->desc;//CommonHelper::get_item_name($row1->item_id);?>

                                    </td>



                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left" style="border: solid 1px black;"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>




                                    <td class="text-right" style="border: solid 1px black;"> <?php if ($row1->bundles_id==0):  echo number_format($row1->qty); else: echo number_format($row1->bqty,2); endif; ?></td>


                                    <td class="text-right bundleHide" style="border: solid 1px black;"><?php   if ($row1->bundles_id==0): echo number_format($row1->rate,2); else: echo number_format($row1->bundle_rate,2); endif;?></td>
                                    <td class="text-right bundleHide" style="border: solid 1px black;"><?php if ($row1->bundles_id==0): echo  number_format($row1->rate*$row1->qty,2); $tttot += $row1->rate*$row1->qty;else: echo number_format($row1->bundle_amount,2); $tttot+=$row1->bundle_amount; endif;?></td>
                                    <td class="text-center"><?php echo number_format($row1->discount_percent,2);?></td>
                                    <td class="text-center"><?php echo number_format($row1->discount_amount,2);?></td>
                                    <td class="text-center"><?php echo number_format($row1->rate*$row1->qty+$row1->discount_amount,2);?></td>

                                    {{--<td class="text-right" style="border: solid 1px black;">< ?php if ($row1->bundles_id==0):  echo number_format($row1->amount,2);else: echo number_format($row1->b_net,2); endif;?></td>--}}

                                </tr>

                                <?php
                                $AttchCount = $counter;
                                else:   ?>


                                <tr  style="font-size: larger;font-weight: bold;background-color: lightyellow">
                                    <td class="text-center" style="border: solid 1px black;"><?php echo $counter++;?></td>
                                    <td  id="" class="text-left" style="border: solid 1px black;"><?php echo $row1->product_name;?></td>
                                    <td class="text-left" style="border: solid 1px black;"> <?php  echo CommonHelper::get_uom_name($row1->bundle_unit);   ?> </td>
                                    <td class="text-right" style="border: solid 1px black;"> <?php echo number_format($row1->bqty,3)?></td>
                                    <td class="text-right  bundleHide" style="border: solid 1px black;"><?php echo number_format($row1->bundle_rate,2);?></td>
                                    <td class="text-right hidee bundleHide" style="border: solid 1px black;"><?php echo number_format($row1->bundle_amount,2);// $tttot+=$row1->bundle_amount?></td>

                                    {{--<td class="text-right hidee" style="border: solid 1px black;">< ?php echo number_format($row1->b_net,2);?></td>--}}

                                </tr>
                                <?php $product_data=DB::Connection('mysql2')->table('delivery_note_data')->where('bundles_id',$row1->bundles_id)
                                ->where('master_id',$id)->select('*')->get();
                                $item_count=1;
                                foreach ($product_data as $bundle_data):

                                $total_amount+=$bundle_data->amount;
                                ?>

                                <input type="hidden" name="item_id{{$working_counter}}" id="item_id{{$working_counter}}" value="{{$bundle_data->item_id}}"/>

                                <tr class="ShowHideHtml">
                                    <td class="text-center" style="border: solid 1px black;"><?php echo $AttchCount.'.'.$item_count++;?></td>
                                    <td id="{{$bundle_data->item_id}}" class="text-left" style="border: solid 1px black;"> <?php echo $bundle_data->desc//CommonHelper::get_item_name($bundle_data->item_id);?></td>


                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left" style="border: solid 1px black;"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                    <td class="text-right" style="border: solid 1px black;"> <?php echo number_format($bundle_data->qty,3)?></td>

                                    <td style="border: solid 1px black;" class="bundleHide">  <?php echo number_format($bundle_data->rate,2)?></td>
                                    <td class="text-right hidee bundleHide" style="border: solid 1px black;"> <?php echo number_format($bundle_data->rate * $bundle_data->qty,2); $tttot+=$bundle_data->rate * $bundle_data->qty;?></td>
                                    <td class="text-center"><?php echo $bundle_data->discount_percent;?></td>
                                    <td class="text-center"><?php echo $bundle_data->discount_amount;?></td>
                                    <td class="text-center"><?php echo number_format($bundle_data->rate * $bundle_data->qty+$bundle_data->discount_amount,2);?></td>
                                    {{--<td class="text-right hidee" style="border: solid 1px black;"> < ?php echo number_format($bundle_data->amount,2)?></td>--}}

                                </tr>
                                <?php

                                $total_discount+=$bundle_data->discount_amount;
                                $working_counter++; endforeach ?>




                                <?php endif; ?>

                                <?php


                                if ($row1->bundles_id!=0):   $total+=$row1->b_net; endif;

                                }
                                ?>

                                <tr class="bundleHide">

                                    <td style="border: solid 1px black;background-color: darkgray" class="text-center" colspan="5">Total</td>
                                    {{--<td  style="border: solid 1px black;background-color: darkgray" class="text-right"  colspan="1">{{number_format($total_amount,2)}}</td>--}}
                                    <td  style="border: solid 1px black;background-color: darkgray" class="text-right"  colspan="1">{{number_format($tttot,2)}}</td>

                                </tr>
                                <?php if($total_discount > 0):?>
                                <tr class="bundleHide">
                                    <td style="border: solid 1px black;background-color: darkgray" class="text-center" colspan="5">Discount</td>
                                    <td  style="border: solid 1px black;background-color: darkgray" class="text-right"  colspan="1">{{number_format($total_discount,2)}}</td>
                                </tr>
                                <?php endif;?>



                                </tbody>
                                @if($delivery_note->sales_tax_amount >0)
                                    <?php  $total+=$delivery_note->sales_tax_amount; ?>
                                    <tr class="bundleHide">
                                        <td class="text-center" style="border: solid 1px black;" colspan="5">Sales Tax 17%</td>
                                        <td class="text-right" colspan="1" style="border: solid 1px black;">{{   number_format($delivery_note->sales_tax_amount,2)}}</td>
                                    </tr>
                                @endif

                                <tr class="bundleHide">

                                    <td style="border: solid 1px black;background-color: darkgray" class="text-center" colspan="5">Grand Total</td>
                                    <td style="border: solid 1px black;background-color: darkgray"  class="text-right" colspan="1">{{number_format($tttot-$total_discount+$delivery_note->sales_tax_amount,2)}}</td>

                                </tr>

                            </table>
                        </div>
                    </div>
                    <div id="printable" style="display: none" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table " style="border: solid 1px black;">
                                <thead>
                                <tr>
                                    <th class="text-center" style="border: solid 1px black;">S.NO</th>
                                    <th class="text-center" style="border: solid 1px black;">Item</th>
                                    <th class="text-center" style="border: solid 1px black;">Uom</th>
                                    <th class="text-center" style="border: solid 1px black;">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $total_qty=0;
                                foreach ($delivery_note_data_other as $row1){


                                ?>
                                <tr title="" id="<?php echo $row1->id?>" ondblclick="diss(this.id)">
                                    <td class="text-center" style="border: solid 1px black;"><?php echo $counter++;?></td>
                                    <td class="text-left" style="border: solid 1px black;">


                                        <?php echo $row1->desc;//CommonHelper::get_item_name($row1->item_id);?>


                                    </td>
                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left" style="border: solid 1px black;"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>
                                    <td style="border: solid 1px black;"> <?php   echo number_format($row1->qty); ?></td>

                                </tr>

                                <?php

                                $total_qty+=$row1->qty;

                                }
                                ?>

                                <tr>

                                    <td style="border: solid 1px black;background-color: darkgray" class="text-center" colspan="3">Total</td>
                                    <td  style="border: solid 1px black;background-color: darkgray" class="text-right"  colspan="1">{{number_format($total_qty,2)}}</td>

                                </tr>


                                </tbody>


                            </table>
                        </div>
                    </div>



                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">

                                <textarea><?php echo 'Description:'.' '.strtoupper($delivery_note->description); ?></textarea>
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
                                        <b>   <p><?php echo strtoupper($delivery_note->username);?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Checked By:</h6>
                                        <b>   <p><?php  ?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b><p></b>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                    <!--
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                        <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request Voucher Detail (Office Use)'))!!} ">
                    </div>
                    <!-->
                </div>
            </div>
        </div>

    </div>

    <script>


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

        function show_hide()
        {
            if($('#formats').is(":checked"))
            {
                $("#actual").css("display", "none");
                $("#printable").css("display", "block");
            }

            else
            {
                $("#actual").css("display", "block");
                $("#printable").css("display", "none");
            }
        }

        function show_hide2()
        {
            if($('#formats2').is(":checked"))
            {
                $(".ShowHideHtml").fadeOut("slow");
                $(".bundleHide").fadeOut("slow");

//                $("#printable").css("display", "block");
            }

            else
            {
                $(".ShowHideHtml").fadeIn("slow");
                $(".bundleHide").fadeIn("slow");

//                $("#printable").css("display", "none");
            }
        }



        function diss(id)
        {
            $('#'+id).remove();
        }


    </script>

