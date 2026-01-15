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
//$creit_note=new CreditNote();
//$creit_note=$creit_note->SetConnection('mysql2');
$MultiIds = $_GET['MultiIds'];
$MasterDetail=DB::Connection('mysql2')->table('credit_note')->whereIn('so_id',array($MultiIds))->where('status',1)->get();
foreach($MasterDetail as $creit_note):

$credit_note_data=DB::Connection('mysql2')->table('credit_note_data')->where('master_id',$creit_note->id)->get();
?>
<div class="row">


</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printPurchaseRequestVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

        <div style="line-height:5px;">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                 style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                                <h3 style="text-align: center;">Credit Note Detail</h3>
                            </div>
                            <br />

                        </div>
                    </div>
                </div>


                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="width:100%; float:left;">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>
                                <tr>
                                    <?php
                                    $SoNo = "";
                                    if($creit_note->so_id > 0):
                                    $SoData = CommonHelper::get_single_row('sales_order','id',$creit_note->so_id);
                                    $SoNo = $SoData->so_no;
                                    else:
                                    $SoNo = "";
                                    endif;
                                    ?>
                                    <td class="text-left" style="width:50%;">SO NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($SoNo);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:50%;">CR NO.</td>
                                    <td class="text-left" style="width:50%;"><?php echo strtoupper($creit_note->cr_no);?></td>
                                </tr>


                                <tr>
                                    <td class="text-left">CR Date</td>
                                    <td class="text-left"><?php echo CommonHelper::changeDateFormat($creit_note->cr_date);?></td>
                                </tr>







                                <?php $customer_data= CommonHelper::byers_name($creit_note->buyer_id);?>
                                <tr>
                                    <td class="text-left">Buyer's Name</td>
                                    <td class="text-left"><?php echo ucwords($customer_data->name)?></td>
                                </tr>



                                <tr>
                                    <td class="text-left">Buyer's Address</td>
                                    <td class="text-left"><?php echo  ucwords($customer_data->address);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left">Description</td>
                                    <td class="text-left"><?php echo  ucwords($creit_note->description);?></td>
                                </tr>




                                </tbody>
                            </table>

                        </div>


                    </div>




                    <label for="">Show Qty <input type="checkbox" id="CheckUnCheck<?php $creit_note->id?>" onclick="ShowHideQty('<?php echo $creit_note->id?>')"></label>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center">S.NO</th>
                                    <th class="text-center">Item</th>
                                    <?php if($creit_note->type == 1):?>
                                    <th class="text-center" id="Heading<?php $creit_note->id?>" style="display: none;">Delivery Not Qty</th>
                                    <?php else:?>
                                    <th class="text-center" id="Heading">Sales Tax Invoice Qty</th>
                                    <?php endif;?>
                                    <th class="text-center" >QTY. </th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Discount %</th>
                                    <th class="text-center">Discount Amount</th>
                                    <th class="text-center">Net Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $counter=1;
                                $TotalAmount=0;
                                $TotalNetAmount = 0;
                                $TotalDisAmount = 0;

                                foreach ($credit_note_data as $row1){
                                $VoucherQty = "";
                                if($row1->type == 1)
                                {
                                $VoucherQty = CommonHelper::get_single_row('delivery_note_data','id',$row1->voucher_data_id);
                                }
                                else
                                {
                                $VoucherQty = CommonHelper::get_single_row('sales_tax_invoice_data','id',$row1->voucher_data_id);
                                }

                                ?>
                                <tr>
                                    <td class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td class="text-left"><?php echo CommonHelper::get_item_name($row1->item);?></td>
                                    <td class="ShowHide<?php $creit_note->id?>" style="display: none;"><?php echo $VoucherQty->qty?></td>
                                    <td> <?php echo number_format($row1->qty)?></td>
                                    <td class="text-right"><?php echo number_format($row1->rate,2);?></td>
                                    <td class="text-right"><?php echo number_format($row1->amount,2); $TotalAmount+=$row1->amount;?></td>
                                    <td class="text-right"><?php echo number_format($row1->discount_percent,2);?></td>
                                    <td class="text-right"><?php echo number_format($row1->discount_amount,2); $TotalDisAmount+=$row1->discount_amount?></td>
                                    <td class="text-right"><?php echo number_format($row1->net_amount,2); $TotalNetAmount+=$row1->net_amount?></td>
                                </tr>

                                <?php
                                //$TotalAmount+=$row1->amount;

                                }
                                ?>

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="4" id="TotalSpan<?php $creit_note->id?>">Total</td>
                                    <td  style="background-color: darkgray" class="text-right" >{{number_format($TotalAmount,2)}}</td>
                                    <td style="background-color: darkgray" class="text-center" colspan="1"></td>
                                    <td  style="background-color: darkgray" class="text-right" >{{number_format($TotalDisAmount,2)}}</td>
                                    <td  style="background-color: darkgray" class="text-right" >{{number_format($TotalNetAmount,2)}}</td>
                                </tr>



                                @if($creit_note->sales_tax >0)
                                    <?php  $TotalNetAmount+=$creit_note->sales_tax; ?>
                                    <tr>
                                        <td class="text-center" colspan="7" id="SalesTaxSpan<?php $creit_note->id?>">Sales Tax 17%</td>
                                        <td class="text-right" colspan="1">{{   number_format($creit_note->sales_tax,2)}}</td>
                                    </tr>
                                @endif


                                @if($creit_note->sales_tax_further >0)
                                    <?php $TotalNetAmount+=$creit_note->sales_tax_further; ?>
                                    <tr>
                                        <td class="text-center" colspan="7" id="FurtherTaxSpan<?php $creit_note->id?>">Sales Tax Further 3%</td>
                                        <td class="text-right" colspan="1">{{   number_format($creit_note->sales_tax_further,2)}}</td>
                                    </tr>
                                @endif

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="7" id="GrandTotalSpan<?php $creit_note->id?>">Grand Total</td>
                                    <td style="background-color: darkgray"  class="text-right" colspan="1">{{number_format($TotalNetAmount,2)}}</td>

                                </tr>


                                </tbody>




                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                        <label for="">Show Voucher <input type="checkbox" id="ShowVoucher" onclick="ViewVoucher()"></label>
                    </div>

                    <?php
                    $Trans = DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$creit_note->cr_no)->orderBy('debit_credit',1);


                    if($Trans->count() > 0){
                    ?>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="ShowVoucherDetail" style="display: none">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
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
                                foreach($Trans->get() as $Fil):
                                ?>
                                <tr class="text-center">
                                    <td><?php echo $TransCounter++;?></td>
                                    <td>
                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                        echo $Accounts->name;
                                        ?>
                                    </td>
                                    <td><?php if($Fil->debit_credit == 1): echo number_format($Fil->amount,2); $DrTot+=$Fil->amount; endif;?></td>
                                    <td><?php if($Fil->debit_credit == 0): echo number_format($Fil->amount,2); $CrTot+=$Fil->amount; endif;?></td>
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



                    <table>
                        <tr><td style="text-transform: capitalize;">Amount In Words : <?php  ?></td></tr>
                    </table>


                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <textarea><?php echo 'Description:'.' '.strtoupper($creit_note->description); ?></textarea>
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
    <?php endforeach;?>
    </div>
    </div>
            </div>
        </div>


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

        function ShowHideQty(Id)
        {
            if($('#CheckUnCheck'+Id).is(":checked"))
            {
                $('#Heading'+Id).fadeIn();
                $('.ShowHide'+Id).fadeIn();
                $("#TotalSpan"+Id).attr('colspan',5);
                $("#SalesTaxSpan"+Id).attr('colspan',8);
                $("#FurtherTaxSpan"+Id).attr('colspan',8);
                $("#GrandTotalSpan"+Id).attr('colspan',8);

            }
            else{
                $('#Heading'+Id).fadeOut();
                $('.ShowHide'+Id).fadeOut();
                $("#TotalSpan"+Id).attr('colspan',4);
                $("#SalesTaxSpan"+Id).attr('colspan',7);
                $("#FurtherTaxSpan"+Id).attr('colspan',7);
                $("#GrandTotalSpan"+Id).attr('colspan',7);
            }
        }

        // View Hidden Voucher Detail 
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




    </script>
@endsection
