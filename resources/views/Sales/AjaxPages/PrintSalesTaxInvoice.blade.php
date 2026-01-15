<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\SalesHelper;
$id = $_GET['id'];
//$m = $_GET['m']; before Code
$m = Session::get('run_company'); //After Code Change
$currentDate = date('Y-m-d');
$total_expense =0;
$AmountInWordsMain=0;

?>

@extends('layouts.default')

@section('content')
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
            border:1px solid blue !important;
        }


    }
</style>
<?php

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a class="btn btn-xs btn-success" href="<?php echo url('sales/CreateSalesTaxInvoiceList?pageType=view&&parentCode=91&&m='.$m)?>">Create Sales Tax Invoice</a>
        <a class="btn btn-xs btn-primary" href="<?php echo url('sales/viewSalesTaxInvoiceList?pageType=view&&parentCode=91&&m='.$m.'')?>">Sales Tax Invoice List</a>
            <button class="btn btn-xs btn-info" onclick="printViewThis('printPurchaseRequestVoucherDetail','','1')" style="">
                <span class="glyphicon glyphicon-print"> Print</span>
            </button>
        <button id="print" value="print"/>
        <button  class="btn btn-sm btn-warning" onclick="expt('sales_Tax_Invoice_data','Sales Tax Invoice','1')" style="width: 100px">
            <span class="glyphicon glyphicon-print">CSV</span>
        </button>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a target="_blank" href="{{url('/sales/undertaking?id='.$sales_tax_invoice->id)}}">UnderTaking A</a>
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
        <div id="" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fo">
            <div class="">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        {{--<label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo CommonHelper::changeDateFormat($currentDate);?></label>--}}
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                 style="font-size: 30px !important; font-style: inherit;
                                    font-family: -webkit-body; font-weight: bold;">
                                <?php echo '<br>';
                                echo '<br>';
                                echo '<br>';
                                //CommonHelper::getCompanyName($m);?>
                                    <h3 style="text-align: center;">COMMERCIAL INVOICE</h3>
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
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                        {{--< ?php $nameOfDay = date('l', strtotime($currentDate)); ?>--}}
                        {{--<label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo '&nbsp;'.$nameOfDay;?></label>--}}

                    </div>
                </div>


                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="width:50%; float:left;
                        ">
                            <table style="border:1px solid black;" class="table sales_Tax_Invoice_data">
                                <tbody>
                                <?php $customer_data= CommonHelper::byers_name($sales_tax_invoice->buyers_id);

                                $sales_order= SalesHelper::get_sales_tax_by_sales_order_id($sales_tax_invoice->so_id);
                                ?>
                                <tr>
                                    <th style="border:1px solid black;width: 50%"  class="text-left" style="border: solid 1px;">BUYER'S NAME</th>
                                    <td style="border:1px solid black; width: 50%" class="text-left"><strong><?php echo ucwords($customer_data->name)?></strong></td>
                                </tr>

                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">BUYER'S ORDER NO.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->order_no);?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:60%; border: solid 1px;">BUYER'S Order Date</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:40%;"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->order_date);?></td>
                                </tr>
                                @if($sales_tax_invoice->so_id != 0):
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:50%;border: solid 1px;">BUYER'S UNIT.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_order->buyers_unit);?></td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">BUYER'S ADDRESS</th>
                                    <td style="border:1px solid black;font-size: xx-small" class="text-left" ><?php echo  ucwords($customer_data->address);?></td>
                                </tr>



                                </tbody>
                            </table>

                        </div>

                        <div style="width:40%; float:right;">
                            <table  style="border:1px solid black;" class="table  sales_Tax_Invoice_data">
                                <tbody>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">SI NO.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->gi_no);?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">SI Date</th>
                                    <td style="border:1px solid black;" class="text-left"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->gi_date);?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">SO NO.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;">
                                        <?php
                                        if($sales_tax_invoice->so_id != 0):
                                        echo strtoupper($sales_tax_invoice->so_no);
                                        else:
                                        echo $sales_tax_invoice->other_refrence;
                                        endif;
                                        ?>
                                    </td>
                                </tr>




                                @if($sales_tax_invoice->so_id != 0):
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">OTHER REFRENCE</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_order->other_refrence);?></td>
                                </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div style="text-align: left" class="printHide">
                        {{--<label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />Printable Format </label>--}}
                        <label class="text-left"><input type="checkbox" onclick="show_hide2()" id="formats2" />Bundle Printable Format </label>
                    </div>

                    <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table style="border:1px solid black;"  class="table  sales_Tax_Invoice_data">
                                <thead>
                                <tr>
                                    <th style="border:1px solid black;" class="text-center">S.NO</th>
                                    <th style="border:1px solid black;" class="text-center">ITEM</th>

                                    <th style="border:1px solid black;" class="text-center" >UOM</th>
                                    <th style="border:1px solid black;" class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th style="border:1px solid black;" class="text-center bundleHide">RATE</th>

                                    <th style="border:1px solid black; " class="text-center bundleHide">NET AMOUNT</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $total=0;
                                $totDisc = 0;
                                $total_cogs=0;
                                foreach ($sales_tax_invoice_data as $row1){

                                if ($row1->bundles_id==0):

                                ?>
                                <tr class="ShowHideHtml">
                                    <td style="border:1px solid black;" class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td style="border:1px solid black;" class="text-left"><?php  $row1->description;//CommonHelper::get_item_name($row1->item_id);?>
                                        <?php

                                        if ($row1->so_type==0):
                                        $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                        $sub_ic_detail= explode(',',$sub_ic_detail);
                                        echo   DB::Connection('mysql2')->table('sales_order_data')->where('id',$row1->so_data_id)->value('desc');
                                        else:
                                        echo $row1->description;
                                        endif;

                                        ?>
                                    </td>

                                    <td style="border:1px solid black;" class="text-left"> <?php  if ($row1->so_type==0): echo CommonHelper::get_uom_name($sub_ic_detail[0]); endif;?></td>

                                    <td style="border:1px solid black;" class="text-center"> <?php echo number_format($row1->qty,2)?></td>

                                    <td style="border:1px solid black;" class="text-right bundleHide"><?php echo number_format($row1->rate,2);?></td>
                                    <?php $amount=$row1->qty*$row1->rate; ?>
                                    <td style="border:1px solid black;" class="text-right bundleHide"><?php echo number_format($amount,2); ?></td>




                                </tr>

                                <?php

                                $total+=$amount;
                                $totDisc+=$row1->discount_amount;

                                else:
                                $product_data=DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('bundles_id',$row1->bundles_id)
                                ->where('status',1)->
                                select('*')->get();



                                $item_count=$counter+0.1;
                                $bundle_stop=1;
                                foreach ($product_data as $bundle_data):


                                $qty=SalesHelper::get_dn_total_qty($bundle_data->id);

                                $qty=$bundle_data->qty-$qty;

                                //  if ($qty>0):

                                $id_count=0;
                                ?>
                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$bundle_data->groupby}}"/>
                                @if ($bundle_stop==1)
                                    <tr  style="font-size: larger;font-weight: bold;background-color: lightyellow" class="ShowHideHtml">
                                        <td  style="border:1px solid black;" class="text-center" class="text-center"><?php echo $counter++;?></td>
                                        <td  style="border:1px solid black;" id="" class="text-left"><?php echo $row1->product_name;?></td>
                                        <td style="border:1px solid black;" class="text-left"> <?php  echo CommonHelper::get_uom_name($row1->bundle_unit);   ?> </td>
                                        <td style="border:1px solid black;" class="text-right"> <?php echo number_format($row1->bqty,3)?></td>

                                        <td style="border:1px solid black;" class="text-right hidee"><?php echo number_format($row1->bundle_rate,2);?></td>
                                        <td style="border:1px solid black;" class="text-right hidee"><?php echo number_format($row1->amount,2);?></td>
                                        {{--<td style="border:1px solid black;" class="text-right hidee">< ?php echo number_format($row1->b_percent,2);?></td>--}}
                                        {{--<td style="border:1px solid black;" class="text-right hidee">< ?php echo number_format($row1->b_dis_amount,2);?></td>--}}
                                        {{--<td style="border:1px solid black;" class="text-right hidee">< ?php echo number_format($row1->b_net,2);?></td>--}}

                                    </tr>
                                    <?php $bundle_stop++ ?>
                                @endif
                                <?php //endif;

                                ?>



                                <tr class="ShowHideHtmlNone">
                                    <td style="border:1px solid black;" class="text-center" class="text-center"><?php echo $item_count;?></td>
                                    <td style="border:1px solid black;" class="text-left"><?php echo $bundle_data->description;//CommonHelper::get_item_name($bundle_data->item_id)?></td>


                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($bundle_data->item_id);
                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                    ?>
                                    <td style="border:1px solid black;" class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                    <td style="border:1px solid black;" class="text-center">{{number_format($bundle_data->qty,2)}}</td>
                                    <td style="border:1px solid black;" class="text-center bundleHide">{{$bundle_data->rate}}</td>

                                    <td style="border:1px solid black;" class="text-center bundleHide">{{$bundle_data->rate*$bundle_data->qty}}</td>

                                    <?php $total+=$bundle_data->rate*$bundle_data->qty;
                                    $totDisc+=$bundle_data->discount_amount;
                                    ?>



                                </tr>

                                <?php    $item_count+=0.1;    endforeach; $counter; endif;

                                }
                                ?>

                                <tr title="{{$total_cogs}}" class="bundleHide">

                                    <td style="border:2px solid black;"  class="text-center" colspan="4"><strong>TOTOAL EXCLUDING SALES TAX</strong></td>
                                    <td style="border:2px solid black;"   class="text-right"  colspan="2"><strong>{{number_format($total,2)}}</strong></td>

                                </tr>


                                </tbody>

                                @if($totDisc>0)
                                    <tr class="bundleHide">
                                        <td style="border:2px solid black;" class="text-center" colspan="4"><strong>Total Discount</strong></td>
                                        <td style="border:2px solid black;" class="text-right" colspan="2"><strong>{{   number_format($totDisc,2)}}</strong></td>
                                    </tr>

                                    <tr class="bundleHide">
                                        <td style="border:2px solid black;" class="text-center" colspan="4"><strong>Total Amount After Discount</strong></td>
                                        <td style="border:2px solid black;" class="text-right" colspan="2"><strong>{{   number_format($total-$totDisc,2)}}</strong></td>
                                    </tr>
                                @endif
                                @if($sales_tax_invoice->sales_tax >0)
                                    <?php  $total+=$sales_tax_invoice->sales_tax; ?>
                                    <tr class="bundleHide">
                                        <td style="border:2px solid black;" class="text-center" colspan="4"><strong>SALES TAX 17%</strong></td>
                                        <td style="border:2px solid black;" class="text-right" colspan="2"><strong>{{   number_format($sales_tax_invoice->sales_tax,2)}}</strong></td>
                                    </tr>
                                @endif


                                @if($sales_tax_invoice->sales_tax_further >0)
                                    <?php $total+=$sales_tax_invoice->sales_tax_further; ?>
                                    <tr class="bundleHide">
                                        <td style="border:2px solid black;" class="text-center" colspan="4"><strong>SALES TAX FURTHER 3%</strong></td>
                                        <td style="border:21px solid black;" class="text-right" colspan="2"><strong>{{   number_format($sales_tax_invoice->sales_tax_further,2)}}</strong></td>
                                    </tr>
                                @endif

                                <?php if($AddionalExpense->count() > 0){?>




                                <tbody>
                                <?php $ExpCounter = 1;

                                foreach($AddionalExpense->get() as $Fil):
                                ?>
                                <tr class="text-center">

                                    <td style="border:2px solid black;" colspan="4">
                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                        ?>
                                        <strong><?php echo strtoupper($Accounts->name);?></strong>
                                    </td>
                                    <td style="border:2px solid black;"  colspan="2" class="text-right"><strong><?php echo number_format($Fil->amount,2);
                                            $total_expense+=$Fil->amount;?></strong></td>
                                </tr>
                                <?php endforeach;?>

                                <?php }?>
                                {{--<tr>--}}

                                {{--<td style="background-color: darkgray" class="text-center" colspan="6">Grand Total</td>--}}
                                {{--<td style="background-color: darkgray"  class="text-right" colspan="2">{{number_format($total,2)}}</td>--}}

                                {{--</tr>--}}
                                <tr class="bundleHide">

                                    <td style="border:2px solid black;"  class="text-center" colspan="4"><strong>GRAND TOTAL</strong></td>
                                    <td style="border:2px solid black;"  class="text-right" colspan="2" id="TotalAmountRec"><strong><?php echo number_format($total-$totDisc+$total_expense,2); $AmountInWordsMain = $total-$totDisc+$total_expense;?></strong></td>

                                </tr>
                                {{--<tr class="text-left"><td style="border:1px solid black;"  colspan="6">Amount In Words : <span style="font-size: smaller" id="rupees"></span></td></tr>--}}
                            </table>
                        </div>
                    </div>


                    <?php


                    ?>

                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div style="text-align: right">

                        </div>
                    </div>
                    <input type="hidden" id="total" value="{{$AmountInWordsMain}}">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left printHide">
                        <label for="">Show Voucher <input type="checkbox" id="ShowVoucher" onclick="ViewVoucher()"></label>
                    </div>

                    <?php
                    $Trans = DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$sales_tax_invoice->gi_no)->orderBy('debit_credit',1);


                    if($Trans->count() > 0){
                    ?>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="ShowVoucherDetail" style="display: none">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                                <thead>
                                <tr>
                                    <td colspan="4"><strong><h4>Voucher</h4></strong></td>
                                </tr>
                                <tr>

                                    <th class="text-center">Sr No</th>
                                    <th class="text-center">Account Head<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Debit<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Credit<span class="rflabelsteric"></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $TransCounter = 1;
                                $DrTot = 0;
                                $CrTot = 0;
                                foreach($Trans->get() as $Fil): ?>
                                <tr class="text-center">
                                    <td style="border:1px solid black;"><?php echo $TransCounter++;?></td>
                                    <td style="border:1px solid black;">
                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                        echo $Accounts->name;
                                        ?>
                                    </td>
                                    <td style="border:1px solid black;"><?php if($Fil->debit_credit == 1): echo number_format($Fil->amount,2); $DrTot+=$Fil->amount; endif;?></td>
                                    <td style="border:1px solid black;"><?php if($Fil->debit_credit == 0): echo number_format($Fil->amount,2); $CrTot+=$Fil->amount; endif;?></td>
                                </tr>
                                <?php endforeach;?>
                                <tr class="text-center">
                                    <td colspan="2">TOTAL</td>
                                    <td><?php echo number_format($DrTot,2)?></td>
                                    <td><?php echo number_format($CrTot,2)?></td>
                                </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                    <?php }?>



                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">

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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Prepared By: </h6>
                                        <b> <p><?php echo strtoupper($sales_tax_invoice->username)?></p></b>
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
<div id="#print-me">

</div>


<script>



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
            $(".ShowHideHtmlNone").fadeOut("slow");
            $(".ShowHideHtml").fadeIn("slow");

//                $("#printable").css("display", "block");
        }

        else
        {
            $(".ShowHideHtmlNone").fadeIn("slow");
            $(".ShowHideHtml").fadeIn("slow");

//                $("#printable").css("display", "none");
        }
    }

    function printViewThis(param1,param2,param3) {


        $( ".qrCodeDiv" ).removeClass( "hidden" );
        if(param2!="")
        {
            $('.'+param2).prop('href','');
        }
        $('.printHide').css('display','none');
        var printContents = document.getElementById(param1).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
        //if(param3 == 0){
        //document.location.href = '< ?php echo url('sales/viewSalesTaxInvoiceList?pageType=view&&parentCode=91&&m='.$m.'#signsnow')?>';

        //}
    }


    $(document).ready(function() {

        toWords(1);

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

    function ViewVoucher()
    {
        if($('#ShowVoucher').is(':checked'))
        {
            $('#ShowVoucherDetail').css('display','block');
        }
        else
        {
            $('#ShowVoucherDetail').css('display','none');
        }
    }







    $("#print").click(function () {


        var content = $("#printPurchaseRequestVoucherDetail").html();
        document.body.innerHTML = content;
        //var content = document.getElementById('header').innerHTML;
        //var content2 = document.getElementById('content').innerHTML;

    });
</script>
@endsection