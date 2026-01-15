<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>


<style>
    textarea {
        border-style: none;
        border-color: Transparent;

    }
</style>
<?php

?>
<div class="row">


</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail','','1');?>
    </div>
</div>
<div class="row" id="printPurchaseRequestVoucherDetail">
    <div class="">

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
                        <h3 style="text-align: center;">Credit Note Detail</h3>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                        {{--< ?php $nameOfDay = date('l', strtotime($currentDate)); ?>--}}
                        {{--<label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo '&nbsp;'.$nameOfDay;?></label>--}}

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
                                    $customer_name='';
                                    $SoNo = "";
                                    if($creit_note->so_id > 0):
                                    $SoData = CommonHelper::get_single_row('sales_order','id',$creit_note->so_id);
                                    $SoNo = $SoData->so_no;
                                    else:
                                    $SoNo = "";
                                    endif;
                                    ?>
                                        @if ($creit_note->type==3)
                                            <td class="text-left" style="width:50%;">POS NO.</td>

                                            <?php

                                            $pos_data=     DB::Connection('mysql2')->table('pos')->where('id',$creit_note->pos_id)->select('pos_no','customer_name')->first();
                                            $customer_name='('.$pos_data->customer_name.')';
                                            ?>
                                            <td class="text-left" style="width:50%;"><?php echo strtoupper($pos_data->pos_no);?></td>
                                        @else

                                            <td class="text-left" style="width:50%;">SO NO.</td>
                                            <td class="text-left" style="width:50%;"><?php echo strtoupper($SoNo);?></td>
                                        @endif
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
                                    <td class="text-left"><?php echo ucwords($customer_data->name).$customer_name?></td>
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




                            <label for="">Show Qty <input type="checkbox" id="CheckUnCheck" onclick="ShowHideQty()"></label>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center">S.NO</th>
                                    <th class="text-center">Item</th>
                                    <?php if($creit_note->type == 1):?>
                                    <th class="text-center" id="Heading" style="display: none;">Delivery Not Qty</th>
                                    <?php else:?>
                                    <th class="text-center" id="Heading">Sales Tax Invoice Qty</th>
                                    <?php endif;?>
                                    <th class="text-center" >Batch Code </th>
                                    <th class="text-center" >Location </th>
                                    <th class="text-center" >QTY. </th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Tax %</th>
                                    <th class="text-center">Tax Amount</th>
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

                                        $Stock = DB::Connection('mysql2')->table('stock')->where('master_id',$row1->id)->first();

                                ?>
                                <tr>
                                    <td class="text-center" class="text-center"><?php echo $counter++;?></td>
                                    <td onclick="" class="text-left">
                                        <a target="_blank"
                                     href="<?php echo url('store/fullstockReportView?sub_item_id='.$row1->item.'&&m='.Session::get('run_company').'&&warehouse_id='.$Stock->warehouse_id)?>">
                                     <?php echo CommonHelper::get_item_name($row1->item);?></a></td>
                                    <td class="ShowHide" style=""><?php echo $VoucherQty->qty?></td>
                                    <td class="text-center"><?php echo $Stock->batch_code?></td>
                                    <td class="text-center"><?php echo CommonHelper::get_name_warehouse($Stock->warehouse_id)?></td>
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

                                    <td style="background-color: darkgray" class="text-center" colspan="7" id="TotalSpan">Total</td>
                                    <td  style="background-color: darkgray" class="text-right" >{{number_format($TotalAmount,2)}}</td>
                                    <td style="background-color: darkgray" class="text-center" colspan="1"></td>
                                    <td  style="background-color: darkgray" class="text-right" >{{number_format($TotalDisAmount,2)}}</td>
                                    <td  style="background-color: darkgray" class="text-right" >{{number_format($TotalNetAmount,2)}}</td>
                                </tr>



                                @if($creit_note->sales_tax >0)
                                    <?php  $TotalNetAmount+=$creit_note->sales_tax; ?>
                                    <tr>
                                        <td class="text-center" colspan="7" id="SalesTaxSpan">Sales Tax 17%</td>
                                        <td class="text-right" colspan="1">{{   number_format($creit_note->sales_tax,2)}}</td>
                                    </tr>
                                @endif


                                @if($creit_note->sales_tax_further >0)
                                    <?php $TotalNetAmount+=$creit_note->sales_tax_further; ?>
                                    <tr>
                                        <td class="text-center" colspan="7" id="FurtherTaxSpan">Sales Tax Further 3%</td>
                                        <td class="text-right" colspan="1">{{   number_format($creit_note->sales_tax_further,2)}}</td>
                                    </tr>
                                @endif

                                <tr>

                                    <td style="background-color: darkgray" class="text-center" colspan="10" id="GrandTotalSpan">Grand Total</td>
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
                                        echo optional($Accounts)->name;
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
                                        <b>   <p><?php echo strtoupper($creit_note->username);  ?></p></b>
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

        function ShowHideQty()
        {
            if($('#CheckUnCheck').is(":checked"))
            {
                $('#Heading').fadeIn();
                $('.ShowHide').fadeIn();
                $("#TotalSpan").attr('colspan',5);
                $("#SalesTaxSpan").attr('colspan',8);
                $("#FurtherTaxSpan").attr('colspan',8);
                $("#GrandTotalSpan").attr('colspan',8);

            }
            else{
                $('#Heading').fadeOut();
                $('.ShowHide').fadeOut();
                $("#TotalSpan").attr('colspan',4);
                $("#SalesTaxSpan").attr('colspan',7);
                $("#FurtherTaxSpan").attr('colspan',7);
                $("#GrandTotalSpan").attr('colspan',7);
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


      function remove()
      {
          $('a').contents().unwrap();
      }

    </script>

