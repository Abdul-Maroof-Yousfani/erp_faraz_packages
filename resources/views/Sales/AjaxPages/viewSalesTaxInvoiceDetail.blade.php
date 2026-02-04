<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\SalesHelper;
$id = $_GET['id'];
//$m = $_GET['m']; before Code
$m = Session::get('run_company'); //After Code Change
$currentDate = date('Y-m-d');
$total_expense =0;
$AmountInWordsMain =0;

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
            border:1px solid blue !important;
        }


    }
</style>
<?php

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
     <br>
        <button class="btn btn-primary"  onclick="printViewTwo('printPurchaseRequestVoucherDetail','','1')" style="">
            <span class="glyphicon glyphicon-print"></span> &nbsp; Print
        </button>

        <?php //CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail','','1');?>


                @if ($sales_tax_invoice->si_status==1)
                <button  id="appro" class="btn btn-sm btn-success" onclick="approve('{{ $sales_tax_invoice->id }}')" style="width: 100px">Approve
            </button>
            @endif
            </button>
    </div>
    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a target="_blank" href="{{url('/sales/undertaking?id='.$sales_tax_invoice->id)}}">UnderTaking A</a>
    </div> -->
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        {{--<label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo CommonHelper::changeDateFormat($currentDate);?></label>--}}
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <?php
                                //echo '<br>';
                                //echo '<br>';
                                //echo '<br>';
                                //CommonHelper::getCompanyName($m);?>
                                <h3 id="tax" style="text-align: center;">SALES  INVOICE</h3>
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
                                    <td style="border:1px solid black; width: 50%" class="text-left"><strong><?php echo ucwords($customer_data->name ?? '')?></strong></td>
                                </tr>

                                <tr class="hide">
                                    <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">BUYER'S ORDER NO.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->order_no);?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:60%; border: solid 1px;">BUYER'S Order Date</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:40%;"><?php echo CommonHelper::changeDateFormat($sales_tax_invoice->order_date);?></td>
                                </tr>
                                @if($sales_tax_invoice->so_id != 0):
                                <tr class="hide">
                                    <th style="border:1px solid black;" class="text-left" style="width:50%;border: solid 1px;">BUYER'S UNIT.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_order->buyers_unit);?></td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">BUYER'S ADDRESS</th>
                                    <td style="border:1px solid black;" class="text-left" ><?php echo  ucwords($customer_data->address ?? '');?></td>
                                </tr>

                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">BUYER'S NTN</th>
                                    <td style="border:1px solid black;" class="text-left" ><?php echo  ucwords($customer_data->cnic_ntn ?? '');?></td>
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

                                        $currency = '-';
                                        if ($sales_tax_invoice->currency != 0):
                                            $currency = $sales_tax_invoice->currencyRelation->curreny;
                                            $currency_rate = $sales_tax_invoice->currency_rate;
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
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">PLACE OF SUPPLY</th>
                                    <td style="border:1px solid black;" class="text-left">{!! $sales_tax_invoice->description !!}</td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">CURRENCY</th>
                                    <td style="border:1px solid black;" class="text-left">{!! $currency !!}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- <div style="text-align: left" class="printHide">
                        {{--<label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />Printable Format </label>--}}
                        <label class="text-left"><input type="checkbox" onclick="show_hide2()" id="formats2" />Bundle Printable Format </label>
                    </div> -->

                    <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  id="tablee" class="table " style="border: solid 1px black;">
                                <thead>
                                <tr>
                                    <th class="text-center" style="border:1px solid black;">S.NO</th>
                                    <th class="text-center" style="border:1px solid black;">Item</th>
                                    <th class="text-center" style="border:1px solid black;">HS Code</th>
                                    <th class="text-center" style="border:1px solid black;">Bags</th>
                                    {{-- <th class="text-center" style="border:1px solid black;">Color</th> --}}
                                    <th class="text-center" style="border:1px solid black;">Uom</th>
                                    <th class="text-center" style="border:1px solid black;">QTY. (KG)</th>
                                    <th class="text-center" style="border:1px solid black;">Rate</th>
                                    <th class="text-center" style="border:1px solid black;">Amount</th>
                                    <th class="text-center" style="border:1px solid black;">Tax %</th>
                                    <th class="text-center" style="border:1px solid black;">Tax Amount</th>
                                    <th class="text-center" style="border:1px solid black;">Further Tax %</th>
                                    <th class="text-center" style="border:1px solid black;">Further Tax Amount</th>
                                    <th class="text-center" style="border:1px solid black;">Net Amount</th>
                                    <!-- <th class="text-center printHide" style="border:1px solid black;">View</th> -->


                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $count=1;
                                     $total_before_tax=0;
                                     $total_tax=0;
                                     $total_after_tax=0;
                                  
                                     $total_tax_amount = 0 ;
                                     $total_qty = 0;
                                    ?>
                                    @foreach ( $sales_tax_invoice_data as $row )

                                        <?php
                                        $total_expense = 0;
                                        $total_before_tax += $row->rate * $row->qty;
                                        $total_tax += $row->tax_amount;
                                        $total_after_tax += $row->amount;
                                        ?>
                                        <tr>
                                            <td style="border:1px solid black;"> {{ $count++ }} </td>
                                            <td style="border:1px solid black;">{{ CommonHelper::get_item_name($row->item_id) }}</td>
                                            <td style="border:1px solid black;">{{ $row->hs_code_id }}</td>
                                            <td style="border:1px solid black;">{{ $row->qty/$row->pack_size }}</td>
                                            {{-- <td style="border:1px solid black;">{{ $row->color }}</td> --}}
                                            <td style="border:1px solid black;">{{ CommonHelper::get_uom($row->item_id) }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ $row->qty }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ number_format($row->rate,2) }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ number_format($row->rate * $row->qty,2) }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ $row->tax }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ number_format($row->tax_amount,2) }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ $row->sales_tax_further_per }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ number_format($row->sales_tax_further,2) }}</td>
                                            <td class="text-right" style="border:1px solid black;">{{ number_format($row->amount,2) }}</td>
                                            @php
                                            $total_tax_amount += $row->tax_amount;
                                            $total_qty += $row->qty;
                                            @endphp
                                        </tr>
                                    @endforeach
                                        
                                    <tr class="text-center" style="font-weight: bold">
                                        <td  colspan="12" style="border:1px solid black;"> Total </td>
                                        <!-- <td class="text-right" colspan="1" style="border:1px solid black;"> {{ number_format($total_qty,2) }} </td>
                                        <td colspan="5"></td> -->

                                        <!-- <td class="text-right hide" colspan="1" style="border:1px solid black;"> {{ number_format($total_before_tax) }} </td>
                                        <td></td>
                                        <td class="text-right hide" colspan="1" style="border:1px solid black;">  {{ number_format($total_tax,2) }} </td> -->
                                        <td class="text-right" style="border:1px solid black;"> {{ number_format($total_after_tax+$total_expense,2) }} </td>
                                    </tr>

                                    <tr class="text-center" style="font-weight: bold">
                                        <td colspan="12" style="border:1px solid black;">Advance Tax</td>
                                        <td class="text-right" style="border:1px solid black;"> {{ number_format($sales_tax_invoice->advance_tax_amount,2) }} </td>
                                    </tr>
                                    <tr class="text-center" style="font-weight: bold">
                                        <td colspan="12" style="border:1px solid black;">Cartage Amount</td>
                                        <td class="text-right" style="border:1px solid black;"> {{ number_format($sales_tax_invoice->cartage_amount,2) }} </td>
                                    </tr>
                                    <?php

                                    $ExpCounter = 1;
                                    ?>
                                    @if($AddionalExpense->count() > 0)
                                        @foreach($AddionalExpense->get() as $Fil)
                                            <tr class="text-center">

                                                <td style="border:1px solid black;" colspan="12">
                                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                                        ?>
                                                    <strong><?php echo $Accounts->name ?? '';?></strong>
                                                </td>
                                                <td style="border:1px solid black;" class="text-right"><strong><?php echo number_format($Fil->amount,2);
                                                                                                                                $total_expense+=$Fil->amount;?></strong></td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    <tr class="text-center" style="font-weight: bold">
                                        <td colspan="12" style="border:1px solid black;">Grand Total</td>
                                        <td class="text-right" style="border:1px solid black;"> {{ number_format($total_after_tax + $total_expense + $sales_tax_invoice->cartage_amount + $sales_tax_invoice->advance_tax_amount,2) }} </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>



                    <?php


                    ?>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="text-align: right">

                    </div>
                        </div>
                <input type="hidden" id="total" value="{{$AmountInWordsMain}}">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left printHide">
                        <label for="">Show Voucher <input type="checkbox" id="ShowVoucher" onclick="ViewVoucher()"></label>
                    </div>

                    <?php
                    $Trans = DB::Connection('mysql2')->table('transactions')->whereIn('status',[1,100])->where('voucher_no',$sales_tax_invoice->gi_no)->orderBy('debit_credit',1);


                    if($Trans->count() > 0){
                    ?>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ShowVoucherDetail" id="ShowVoucherDetail" style="display: none">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                                <thead>
                                <tr>
                                    <td colspan="4"><strong><h4>Sales Invoice</h4></strong></td>
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
                                foreach($Trans->where('voucher_type',6)->get() as $Fil): ?>
                                <tr class="text-center">
                                    <td style="border:1px solid black;"><?php echo $TransCounter++;?></td>
                                    <td style="border:1px solid black;">
                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                        echo $Accounts->name ?? '';
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




                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ShowVoucherDetail" id="" style="display: none">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                                <thead>
                                <tr>
                                    <td colspan="4"><strong><h4>COGS</h4></strong></td>
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
                                $Trans = DB::Connection('mysql2')->table('transactions')->whereIn('status',[1,100])->where('voucher_no',$sales_tax_invoice->gi_no)->orderBy('debit_credit',1);
                                foreach($Trans->where('voucher_type',8)->get() as $Fil): ?>
                                <tr class="text-center">
                                    <td style="border:1px solid black;"><?php echo $TransCounter++;?></td>
                                    <td style="border:1px solid black;">
                                        <?php $Accounts = CommonHelper::get_single_row('accounts','id',$Fil->acc_id);
                                        echo $Accounts->name ?? '';
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


                    <?php }?>



                    <div style="line-height:8px;">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left hide">
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Prepared By: </h6>
                                        <b> <p><?php echo strtoupper($sales_tax_invoice->username)?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b>   <p><?php echo strtoupper($sales_tax_invoice->approve_user_1)  ?></p></b>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b>  <p><?php echo strtoupper($sales_tax_invoice->approve_user_2)  ?></p></b>
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

        $(document).ready(function() {

            toWords(1);
            var tax = '{{ $total_tax_amount }}';
            if (tax ==0)
            {
                    $('#tax').html('Commerical Invoice');
            }

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
                $('.ShowVoucherDetail').css('display','block');
            }
            else
            {
                $('.ShowVoucherDetail').css('display','none');
            }
        }




        var th = ['','Thousand','Million', 'Billion','Trillion'];
        var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine'];
        var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen'];
        var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
        function toWords(id) {

            s = $('#total').val();


            s = s.toString();
            s = s.replace(/[\, ]/g,'');
            if (s != parseFloat(s)) return 'not a number';
            var x = s.indexOf('.');
            if (x == -1)
                x = s.length;
            if (x > 15)
                return 'too big';
            var n = s.split('');
            var str = '';
            var sk = 0;
            for (var i=0;   i < x;  i++) {
                if ((x-i)%3==2) {
                    if (n[i] == '1') {
                        str += tn[Number(n[i+1])] + ' ';
                        i++;
                        sk=1;
                    } else if (n[i]!=0) {
                        str += tw[n[i]-2] + ' ';
                        sk=1;
                    }
                } else if (n[i]!=0) { // 0235
                    str += dg[n[i]] +' ';
                    if ((x-i)%3==0) str += 'hundred ';
                    sk=1;
                }
                if ((x-i)%3==1) {
                    if (sk)
                        str += th[(x-i-1)/3] + ' ';
                    sk=0;
                }
            }

            if (x != s.length) {
                var y = s.length;
                str += 'point ';
                for (var i=x+1; i<y; i++)
                    str += dg[n[i]] +' ';
            }
            result = str.replace(/\s+/g,' ')+'Only';

            $('#rupees').text(result);
            $('#rupees'+id).text(result);
            $('#rupees').val(result);
            $('#rupeess'+id).val(result);

            var currency=  $('#curren :selected').text();
            currency=currency.split('-');
            var text=$('#rupees').text();
            text=text +' '+'' + currency[0] + '';

            $('#rupees').text(text);


        };

        $('.btn-info').click(function(){
            $('.printHide').css('display','none');
        });


        $("#print").click(function () {


            var content = $("#printPurchaseRequestVoucherDetail").html();
            document.body.innerHTML = content;
            //var content = document.getElementById('header').innerHTML;
            //var content2 = document.getElementById('content').innerHTML;

        });


        function approve(id)
        {
            $("#appro").attr("disabled", true);
            $.ajax
            ({
                url: '{{ url('sales/si_approve') }}',
                type: 'Get',
                data: {id:id},

                success: function (response)
                 {
                    if (response == 0)
                    {
                        alert('stock not avaiable');
                        return;
                    }
                    $('#stat'+id).html(response);
                    $('#showDetailModelOneParamerter').modal('hide');

                }
            })
        }
    </script>
