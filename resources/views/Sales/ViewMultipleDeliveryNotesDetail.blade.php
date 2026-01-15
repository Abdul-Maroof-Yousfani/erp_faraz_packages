<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;

//echo ReuseableCode::average_cost_sales(4852,1,0); die();

//$m = $_GET['m'];
        $MultiIds = $_GET['MultiIds'];
        //print_r($MultiIds);
        $MasterData = DB::Connection('mysql2')->select('select * from delivery_note where id in ('.$MultiIds.') and status = 1');
//print_r($MasterData);
$currentDate = date('Y-m-d');

//$delivery_note=new DeliveryNote();
//$delivery_note=$delivery_note->SetConnection('mysql2');
        ?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
        <?php
//$MasterData=DB::Connection('mysql2')->table('delivery_note')->whereIn('master_id',array($MultiIds))->where('status',1)->get();
foreach($MasterData as $delivery_note):

$delivery_note_data=DB::Connection('mysql2')->select('select a.id,a.master_id,a.qty,a.rate,a.amount,a.bundles_id,
        a.item_id,a.discount_percent,a.discount_amount,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from delivery_note_data a

        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.master_id="'.$delivery_note->id.'"

        group by a.groupby');


$delivery_note_data_other=DB::Connection('mysql2')->table('delivery_note_data')->where('master_id',$delivery_note->id)->get();
?>


<style>
    textarea {
        border-style: none;
        border-color: Transparent;

    }
</style>
<?php

?>

<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printPurchaseRequestVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <!--
        < ?php  StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m,$sales_order->purchase_request_status,$sales_order->status,$row->id,'purchase_request_no','purchase_request_status','status','purchase_request','purchase_request_data');?>
    </div>
    <!-->
        <div style="line-height:5px;">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                 style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                                <?php // echo CommonHelper::getCompanyName($m);?>
                                <h3 style="text-align: center;">Delivery Note</h3>
                            </div>
                            <br />
                            <!--
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                 style="font-size: 20px !important; font-style: inherit;
                                        font-family: -webkit-body; font-weight: bold;">
                                < ?php StoreHelper::checkVoucherStatus($sales_order->purchase_request_status,$sales_order->status);?>
                            </div>
                            <!-->
                        </div>
                    </div>

                </div>


                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="width:40%; float:left;">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>
                                <tr>
                                    <td class="text-left" style="width:50%;">DN NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($delivery_note->gd_no);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left">DN Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($delivery_note->gd_date);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left">Mode / Terms_Of Payment</td>
                                    <td class="text-left"><?php echo $delivery_note->model_terms_of_payment;?></td>
                                </tr>
                                <?php $customer_data= CommonHelper::byers_name($delivery_note->buyers_id);?>
                                <tr>
                                    <td class="text-left">Buyer's Name</td>
                                    <td class="text-left"><?php echo ucwords($customer_data->name)?></td>
                                </tr>



                                <tr>
                                    <td class="text-left">Buyer's Address</td>
                                    <td class="text-left"><?php echo  ucwords($customer_data->address);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Despatch Document No</td>
                                    <td class="text-left"><?php echo  ucwords($delivery_note->despacth_document_no);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Despatch Document Date</td>
                                    <td class="text-left"><?php echo  CommonHelper::changeDateFormat($delivery_note->despacth_document_date);?></td>
                                </tr>


                                </tbody>
                            </table>

                        </div>

                        <div style="width:40%; float:right;">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>

                                <tr>
                                    <td class="text-left" style="width:50%;">SO NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($delivery_note->so_no);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left">SO Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($delivery_note->so_date);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left" style="width:60%;">Buyer's Order Date</td>
                                    <td class="text-left" style="width:40%;"><?php echo CommonHelper::changeDateFormat($delivery_note->order_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Other Reference(S)</td>
                                    <td class="text-left"><?php echo $delivery_note->other_refrence?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;">Despatch Through</td>
                                    <td class="text-left" style="width:40%;"><?php echo $delivery_note->despacth_through;?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;">Destination</td>
                                    <td class="text-left" style="width:40%;"><?php echo $delivery_note->destination;?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="width:60%;">Terms Of Delivery</td>
                                    <td class="text-left" style="width:40%;"><?php echo $delivery_note->terms_of_delivery;?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="width:60%;">Due Date </td>
                                    <td class="text-left" style="width:40%;"><?php echo CommonHelper::changeDateFormat($delivery_note->due_date);?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="text-align: left">
                        <label class="text-left"><input type="checkbox" onclick="show_hide('<?php echo $delivery_note->id?>')" id="formats<?php echo $delivery_note->id?>" />Printable Format </label>
                    </div>



                    <div id="actual<?php echo $delivery_note->id?>" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center">S.NO</th>
                                    <th class="text-center">Item</th>

                                    <th class="text-center" >Uom</th>




                                    <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>

                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Discount%</th>
                                    <th class="text-center">Discount Amount</th>
                                    <th class="text-center">Net Amount</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $total=0;
                                $total_amount=0;
                                $working_counter =0;
                                foreach ($delivery_note_data as $row1){

                                if ($row1->bundles_id==0):
                                $total_amount+=$row1->amount;
                                ?>

                                <tr title="">
                                    <td class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-left">


                                        <?php echo CommonHelper::get_item_name($row1->item_id);?>

                                    </td>



                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>




                                    <td> <?php if ($row1->bundles_id==0):  echo number_format($row1->qty); else: echo number_format($row1->bqty,2); endif; ?></td>


                                    <td class="text-right"><?php   if ($row1->bundles_id==0): echo number_format($row1->rate,2); else: echo number_format($row1->bundle_rate,2); endif;?></td>
                                    <td class="text-right"><?php if ($row1->bundles_id==0): echo  number_format($row1->rate*$row1->qty,2);else: echo number_format($row1->bundle_amount,2); endif;?></td>
                                    <td class="text-right"><?php if ($row1->bundles_id==0): echo number_format($row1->discount_percent,2);else: echo number_format($row1->b_percent,2); endif;?></td>
                                    <td class="text-right"><?php if ($row1->bundles_id==0): echo  number_format($row1->discount_amount,2);else: echo number_format($row1->b_dis_amount,2); endif;?></td>
                                    <td class="text-right"><?php if ($row1->bundles_id==0):  echo number_format($row1->amount,2);else: echo number_format($row1->b_net,2); endif;?></td>

                                </tr>
                                <?php    else:   ?>


                                <tr  style="font-size: larger;font-weight: bold;background-color: lightyellow">
                                    <td class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td  id="" class="text-left"><?php echo $row1->product_name;?></td>
                                    <td class="text-left"> <?php  echo CommonHelper::get_uom_name($row1->bundle_unit);   ?> </td>
                                    <td class="text-right"> <?php echo number_format($row1->bqty,3)?></td>
                                    <td class="text-right "><?php echo number_format($row1->bundle_rate,2);?></td>
                                    <td class="text-right "><?php echo number_format($row1->bundle_amount,2);?></td>
                                    <td class="text-right "><?php echo number_format($row1->b_percent,2);?></td>
                                    <td class="text-right "><?php echo number_format($row1->b_dis_amount,2);?></td>
                                    <td class="text-right "><?php echo number_format($row1->b_net,2);?></td>

                                </tr>
                                <?php $product_data=DB::Connection('mysql2')->table('delivery_note_data')->where('bundles_id',$row1->bundles_id)
                                ->where('master_id',$delivery_note->id)->select('*')->get();
                                $item_count=1;
                                foreach ($product_data as $bundle_data):

                                $total_amount+=$bundle_data->amount;
                                ?>

                                <input type="hidden" name="item_id{{$working_counter}}" id="item_id{{$working_counter}}" value="{{$bundle_data->item_id}}"/>

                                <tr>
                                    <td class="text-center" class="text-center"><?php echo $item_count++;?></td>
                                    <td id="{{$bundle_data->item_id}}" class="text-left"><?php echo CommonHelper::get_item_name($bundle_data->item_id);?></td>


                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                    <td class="text-right"> <?php echo number_format($bundle_data->qty,3)?></td>

                                    <td>  <?php echo number_format($bundle_data->rate,2)?></td>
                                    <td class="text-right hidee"> <?php echo number_format($bundle_data->rate * $bundle_data->qty,2)?></td>
                                    <td class="text-right hidee"> <?php echo number_format($bundle_data->discount_percent,2)?></td>
                                    <td class="text-right hidee"> <?php echo number_format($bundle_data->discount_amount,2)?></td>
                                    <td class="text-right hidee"> <?php echo number_format($bundle_data->amount,2)?></td>

                                </tr>
                                <?php $working_counter++; endforeach ?>




                                <?php endif; ?>

                                <?php


                                if ($row1->bundles_id!=0):   $total+=$row1->b_net; endif;

                                }
                                ?>

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="8">Total</td>
                                    <td  style="background-color: darkgray" class="text-right"  colspan="1">{{number_format($total_amount,2)}}</td>

                                </tr>


                                </tbody>
                                @if($delivery_note->sales_tax_amount >0)
                                    <?php  $total+=$delivery_note->sales_tax_amount; ?>
                                    <tr>
                                        <td class="text-center" colspan="8">Sales Tax 17%</td>
                                        <td class="text-right" colspan="1">{{   number_format($delivery_note->sales_tax_amount,2)}}</td>
                                    </tr>
                                @endif

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="8">Grand Total</td>
                                    <td style="background-color: darkgray"  class="text-right" colspan="1">{{number_format($total_amount+$delivery_note->sales_tax_amount,2)}}</td>

                                </tr>

                            </table>
                        </div>
                    </div>
                    <div id="printable<?php echo $delivery_note->id?>" style="display: none" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center">S.NO</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center" >Uom</th>
                                    <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $total_qty=0;
                                foreach ($delivery_note_data_other as $row1){


                                ?>
                                <tr title="">
                                    <td class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-left">


                                        <?php echo CommonHelper::get_item_name($row1->item_id);?>


                                    </td>
                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>
                                    <td> <?php   echo number_format($row1->qty); ?></td>

                                </tr>

                                <?php

                                $total_qty+=$row1->qty;

                                }
                                ?>

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="3">Total</td>
                                    <td  style="background-color: darkgray" class="text-right"  colspan="1">{{number_format($total_qty,2)}}</td>

                                </tr>


                                </tbody>


                            </table>
                        </div>
                    </div>



                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

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
                    <!--
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                        <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request Voucher Detail (Office Use)'))!!} ">
                    </div>
                    <!-->
                </div>
            </div>
        </div>

    </div>
</div>
<?php endforeach;?>

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

        function show_hide(Id)
        {
            if($('#formats'+Id).is(":checked"))
            {
                $("#actual"+Id).css("display", "none");
                $("#printable"+Id).css("display", "block");
            }

            else
            {
                $("#actual"+Id).css("display", "block");
                $("#printable"+Id).css("display", "none");
            }
        }





    </script>

@endsection
