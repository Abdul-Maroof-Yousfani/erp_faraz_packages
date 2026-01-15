

<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$id = $_GET['id'];
$m = Session::get('run_company');
$currentDate = date('Y-m-d');
$total_expense =0;

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
        <?php CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail','','1');?>
        @if ($sales_order->so_status==0)
                <button  id="appro" class="btn btn-sm btn-success" onclick="approve('{{ $sales_order->id }}')" style="width: 100px">Approve
            </button>
            @endif
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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 sales_Tax_Invoice">
            <div class="">

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
                        <h3 style="text-align: center;">Sales Order</h3>
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
                            <table  class="table " style="border: solid 1px black;">
                                <tbody>

                                <?php $customer_data= CommonHelper::byers_name($sales_order->buyers_id);?>
                                <tr>
                                    <td class="text-left" style="border:1px solid black;">Buyer's Name</td>
                                    <td class="text-left" style="border:1px solid black;"><?php echo ucwords($customer_data->name)?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;border:1px solid black;">Buyer's Order NO</td>
                                    <td class="text-left" style="width:40%;"><?php echo $sales_order->order_no.' ';    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border:1px solid black;width:60%;">Buyer's Order Date</td>
                                    <td class="text-left" style="border:1px solid black;width:40%;"><?php echo CommonHelper::changeDateFormat($sales_order->order_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border:1px solid black;width:60%;">Buyer's Unit</td>
                                    <td class="text-left" style="border:1px solid black;width:40%;"><?php echo $sales_order->buyers_unit;?></td>
                                </tr>
                                <tr>
                                    <td class="text-left">Buyer's Address</td>
                                    <td style="border:1px solid black;font-size: xx-small" class="text-left"><?php echo  ucwords($customer_data->address);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;">Agent</td>
                                    <td style="border:1px solid black;" class="text-left" style="width:40%;"><?php  echo CommonHelper::get_sub_dept_name($sales_order->department) ?></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div style="width:50%; float:right;">
                            <table  class="table " style="border: solid 1px black;">
                                <tbody>


                                <tr>
                                    <td class="text-left" style="border:1px solid black;width:50%;">SO NO.</td>
                                    <td class="text-left" style="border:1px solid black;width:50%;"><?php echo strtoupper($sales_order->so_no);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border:1px solid black;">SO Date</td>
                                    <td class="text-left" style="border:1px solid black;"><?php echo CommonHelper::changeDateFormat($sales_order->so_date);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="border:1px solid black;width:60%;">Terms Of Delivery</td>
                                    <td class="text-left" style="border:1px solid black;width:40%;"><?php echo $sales_order->terms_of_delivery;?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border:1px solid black;">Other Reference(S)</td>
                                    <td class="text-left" style="border:1px solid black;"><?php echo $sales_order->other_refrence?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="width:60%;">Verified</td>
                                    <td class="text-left" style="width:40%;"><?php  if ($sales_order->verified==''): echo   '&#10006;'; else: echo '&#x2714;';   endif ?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="width:60%;">Commission</td>
                                    <td style="border:1px solid black;" class="text-left" style="width:40%;"><?php  echo $sales_order->commission ?></td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>

                <div style="text-align: left" class="printHide">
                    <label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />Printable Format </label>
                    <label class="text-left"><input type="checkbox" onclick="show_hide()" id="formatss" />Other Format </label>
                    </div>



                    <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  id="tablee" class="table " style="border: solid 1px black;">
                                <thead>
                                <tr>
                                    <th class="text-center" style="border:1px solid black;">S.NO</th>
                                    <th class="text-center" style="border:1px solid black;">Item</th>
                                    <th class="text-center" style="border:1px solid black;">Uom</th>
                                    <th class="text-center" style="border:1px solid black;">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center" style="border:1px solid black;">No Of Carton. <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center" style="border:1px solid black;">Rate</th>
                                    <th class="text-center" style="border:1px solid black;">Amount</th>
                                    <th class="text-center" style="border:1px solid black;">Tax %</th>
                                    <th class="text-center" style="border:1px solid black;">Tax Amount</th>
                                    <th class="text-center" style="border:1px solid black;">Net Amount</th>
                                    <th class="text-center printHide" style="border:1px solid black;">View</th>


                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $count=1;
                                     $total_before_tax=0;
                                     $total_tax=0;
                                     $total_after_tax=0;
                                    ?>
                                    @foreach ( $sales_order_data as $row )

                                    <?php

                                    $total_before_tax += $row->sub_total;
                                    $total_tax += $row->tax_amount;
                                    $total_after_tax += $row->amount;
                                    ?>
                                    <tr>
                                    <td style="border:1px solid black;"> {{ $count++ }} </td>
                                    <td style="border:1px solid black;">{{  CommonHelper::get_item_name($row->item_id) }}</td>
                                    <td style="border:1px solid black;">{{ CommonHelper::get_uom($row->item_id) }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ $row->qty }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ $row->no_of_carton }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ number_format($row->rate,2) }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ number_format($row->sub_total,2) }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ $row->tax }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ number_format($row->tax_amount,2) }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ number_format($row->amount,2) }}</td>
                                    </tr>
                                    @endforeach

                                    <tr style="font-size: large;font-weight: bold">
                                        <td  colspan="6" style="border:1px solid black;"> Total </td>
                                        <td class="text-right" colspan="1" style="border:1px solid black;"> {{ number_format($total_before_tax) }} </td>
                                        <td></td>
                                        <td class="text-right" colspan="1" style="border:1px solid black;">  {{ number_format($total_tax,2) }} </td>
                                        <td class="text-right" colspan="1" style="border:1px solid black;"> {{ number_format($total_after_tax,2) }} </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row text-left">
                            <div style="display: none" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <p><?php echo 'Description:'.' '.strtoupper($sales_order->description); ?></p>
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
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-center">
                                        <h6 class="">Prepared By: </h6>
                                        <b>   <p><?php echo strtoupper($sales_order->username);  ?></p></b>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-center">
                                        <h6 class="">Approved By:</h6>
                                        <b>  <p><?php echo strtoupper($sales_order->approve_user_1);  ?></p></b>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-center">
                                        <h6 class="">Approved By:</h6>
                                        <b>  <p><?php echo strtoupper($sales_order->approve_user_2);  ?></p></b>
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-center">
                                        <h6 class="">Approved By:</h6>
                                        <b>  <p><?php echo strtoupper($sales_order->approve_user_3);  ?></p></b>
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

        function view_history(id) {

            var v = $('#sub_' + id).val();


            if ($('#view_history' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelTwoParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
                }
                else {
                    alert('Select Item');
                }

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


        function show_hide()
        {
            if($('#formats').is(":checked"))
            {
                $("#actual").css("display", "none");
                $("#printable").css("display", "block");
                $("#other_fomrate").css("display", "none");
            }

            else
            {
                $("#actual").css("display", "block");
                $("#printable").css("display", "none");
                $("#other_fomrate").css("display", "none");
            }

            if($('#formatss').is(":checked"))
            {
                $("#actual").css("display", "none");
                $("#printable").css("display", "none");
                $("#other_fomrate").css("display", "block");
            }
        }


        function approve(id)
        {
            $("#appro").attr("disabled", true);
            $.ajax
            ({
                url: '{{ url('sales/approve_so') }}',
                type: 'Get',
                data: {id:id},

                success: function (response)
                 {
                    $('#stat'+id).html(response);
                    $('#showDetailModelOneParamerter').modal('hide');

                }
            })
        }
    </script>

