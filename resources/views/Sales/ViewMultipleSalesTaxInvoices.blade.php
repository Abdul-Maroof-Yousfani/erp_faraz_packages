<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
//$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>


<style>
    textarea {
        border-style: none;
        border-color: Transparent;

    }
</style>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
<?php
        $MultiIds = $_GET['MultiIds'];
//$sales_tax_invoice=new SalesTaxInvoice();
//$sales_tax_invoice=$sales_tax_invoice->SetConnection('mysql2');
$MasterDetail=DB::Connection('mysql2')->table('sales_tax_invoice')->whereIn('so_id',array($MultiIds))->where('status',1)->get();
foreach($MasterDetail as $sales_tax_invoice):

$sales_tax_invoice_data=DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id',$sales_tax_invoice->id)->get();

$AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id',$sales_tax_invoice->id);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a target="_blank" href="{{url('/sales/undertaking?id='.$sales_tax_invoice->id)}}">UnderTaking</a>
    </div>
</div>
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
                                <h3 style="text-align: center;">Sales Tax Invoice</h3>
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
                                    <td class="text-left" style="width:50%;">SI NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->gi_no);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left">SI Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->gi_date);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="width:50%;">DN NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->gd_no);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left">DN Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->gd_date);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left">Mode / Terms_Of Payment</td>
                                    <td class="text-left"><?php echo $sales_tax_invoice->model_terms_of_payment;?></td>
                                </tr>
                                <?php $customer_data= CommonHelper::byers_name($sales_tax_invoice->buyers_id);?>
                                <tr>
                                    <td class="text-left">Buyer's Name</td>
                                    <td class="text-left"><?php echo ucwords($customer_data->name)?></td>
                                </tr>



                                <tr>
                                    <td class="text-left">Buyer's Address</td>
                                    <td class="text-left"><?php echo  ucwords($customer_data->address);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Despatched Document No</td>
                                    <td class="text-left"><?php echo  ucwords($sales_tax_invoice->despacth_document_no);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Despatched Document Date</td>
                                    <td class="text-left"><?php echo  CommonHelper::changeDateFormat($sales_tax_invoice->despacth_document_date);?></td>
                                </tr>


                                </tbody>
                            </table>

                        </div>

                        <div style="width:40%; float:right;">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>

                                <tr>
                                    <td class="text-left" style="width:50%;">SO NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->so_no);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left">SO Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->so_date);?></td>
                                </tr>




                                <tr>
                                    <td class="text-left" style="width:60%;">Buyer's Order Date</td>
                                    <td class="text-left" style="width:40%;"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->order_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Other Reference(S)</td>
                                    <td class="text-left"><?php echo $sales_tax_invoice->other_refrence?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;">Despatched Through</td>
                                    <td class="text-left" style="width:40%;"><?php echo $sales_tax_invoice->despacth_through;?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;">Destination</td>
                                    <td class="text-left" style="width:40%;"><?php echo $sales_tax_invoice->destination;?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="width:60%;">Terms Of Delivery</td>
                                    <td class="text-left" style="width:40%;"><?php echo $sales_tax_invoice->terms_of_delivery;?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="width:60%;">Due Date </td>
                                    <td class="text-left" style="width:40%;"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->due_date);?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center">S.NO</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center" >Uom</th>
                                    <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Discount%</th>
                                    <th class="text-center">Discount Amount</th>
                                    <th class="text-center">Net Amount</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $total=0;

                                foreach ($sales_tax_invoice_data as $row1){


                                ?>
                                <tr>
                                    <td class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-left"><?php echo CommonHelper::get_item_name($row1->item_id);?></td>
                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                    <td> <?php echo number_format($row1->qty)?></td>

                                    <td class="text-right"><?php echo number_format($row1->rate,2);?></td>
                                    <td class="text-right"><?php echo number_format($row1->discount,2);?></td>
                                    <td class="text-right"><?php echo number_format($row1->discount_amount,2);?></td>
                                    <td class="text-right"><?php echo number_format($row1->amount,2);?></td>

                                </tr>

                                <?php

                                $total+=$row1->amount;

                                }
                                ?>

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="7">Total</td>
                                    <td  style="background-color: darkgray" class="text-right"  colspan="5">{{number_format($total,2)}}</td>

                                </tr>


                                </tbody>
                                @if($sales_tax_invoice->sales_tax >0)
                                    <?php  $total+=$sales_tax_invoice->sales_tax; ?>
                                    <tr>
                                        <td class="text-center" colspan="7">Sales Tax 17%</td>
                                        <td class="text-right" colspan="5">{{   number_format($sales_tax_invoice->sales_tax,2)}}</td>
                                    </tr>
                                @endif


                                @if($sales_tax_invoice->sales_tax_further >0)
                                    <?php $total+=$sales_tax_invoice->sales_tax_further; ?>
                                    <tr>
                                        <td class="text-center" colspan="7">Sales Tax Further 3%</td>
                                        <td class="text-right" colspan="5">{{   number_format($sales_tax_invoice->sales_tax_further,2)}}</td>
                                    </tr>
                                @endif

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="7">Grand Total</td>
                                    <td style="background-color: darkgray"  class="text-right" colspan="5">{{number_format($total,2)}}</td>

                                </tr>

                            </table>
                        </div>
                    </div>

                    <table>
                        <tr><td style="text-transform: capitalize;">Amount In Words : <?php echo $sales_tax_invoice->amount_in_words ?></td></tr>
                    </table>

                    <?php if($AddionalExpense->count() > 0){?>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <td colspan="3"><strong><h4>Addional Expense</h4></strong></td>
                                </tr>
                                <tr>

                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">Ordered Qty	<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Received Qty<span class="rflabelsteric"></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $ExpCounter = 1;
                                foreach($AddionalExpense->get() as $Fil):
                                ?>
                                <tr class="text-center">
                                    <td><?php echo $ExpCounter++;?></td>
                                    <td>
                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                        echo $Accounts->name;
                                        ?>
                                    </td>
                                    <td><?php echo $Fil->amount?></td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }?>


                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <?php echo 'Description:'.' '.strtoupper($sales_tax_invoice->description); ?>
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

    </div></div></div>
<script>

    $(document).ready(function() {
        //  $('.hidee').fadeOut();

    });
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






</script>
@endsection