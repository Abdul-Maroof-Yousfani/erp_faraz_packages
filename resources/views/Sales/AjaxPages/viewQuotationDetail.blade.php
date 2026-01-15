<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
$id = $_GET['id'];
$m = $_GET['m'];

$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$Quotation = DB::table('quotation')->where('id','=',$id)->first();
$QuotationData = DB::table('quotation_data')->where('master_id','=',$id)->get();
CommonHelper::reconnectMasterDatabase();

?>
<style>

    .signature_bor {
        border-top:solid 1px #CCC;
        padding-top:7px;
    }
    /*.table-bordered > thead > tr > th, .table-bordered > thead > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {*/
        /*border: 1px solid #000;*/
    /*}*/

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($Quotation->quotation_status == 1):?>
        <button type="button" class="btn btn-xs btn-success" id="BtnApproved" onclick="ApprovedQuotation('<?php echo $Quotation->id?>')">Approved</button>
        <?php endif;?>
        <?php CommonHelper::displayPrintButtonInView('printCashSaleVoucherDetail','','1');?>
           <button type="button" class="btn btn-sm btn-primary" onclick="PrintFunc('printCashSaleVoucherDetail')">Print</button>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printCashSaleVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php //echo CommonHelper::getCompanyName($m);?>
                            <h3 style="text-align: center;">Quotation Detail</h3>
                        </div>
                        <br />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php //PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status);?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-right">
                    <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                    <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                </div>
            </div>
            <div class="row">

                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <img style="text-align: center; width: 30%" src="{{url('/storage/app/uploads/left.png')}}">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?php //$nameOfDay = date('l', strtotime($currentDate)); ?>
                    <img style="text-align: center; width: 24%" src="{{url('/storage/app/uploads/right.png')}}">
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 signature_bor"></div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:45%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <?php
                            $client_data=CommonHelper::get_client_data_by_id($Quotation->client_id);
                            $client_name=$client_data->client_name;
                            $ntn=$client_data->ntn;
                            $strn=$client_data->strn;
                            $address=$client_data->address;
                            ?>
                            <tr>
                                {{--<td style="width: 30% !important">Quotation To</td>--}}
                                <td>{{$Quotation->quotation_to}}</td>
                            </tr>
                            <tr>
                                {{--<td>Designation</td>--}}
                                <td>{{$Quotation->designation}}</td>
                            </tr>
                            <tr>
                                {{--<td>Address</td>--}}
                                <td>{{$address}}</td>
                            </tr>


                            {{--<tr>--}}
                                {{--<td style="width:50%;">Client Name.</td>--}}
                                {{--<td style="width:50%;">{{$client_name}}</td>--}}
                            {{--</tr>--}}

                            </tbody>
                        </table>
                    </div>

                    <div style="width:45%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:60%;">Quotation No.</td>
                                <td style="width:40%;">{{$Quotation->quotation_no}}</td>
                            </tr>
                            {{--<tr>--}}
                                {{--<td style="width:60%;">Survey/Tracking No</td>--}}
                                {{--<td style="width:40%;">{{$Quotation->tracking_no}}</td>--}}
                            {{--</tr>--}}
                            <tr style="cursor: pointer" id="ntn" ondblclick="remove(this.id)">
                                <td>NTN No</td>
                                <td>{{$ntn}}</td>
                            </tr>
                            <tr style="cursor: pointer" id="strn" ondblclick="remove(this.id)">
                                <td>STRN No</td>
                                <td>{{$strn}}</td>
                            </tr>



                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:40%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr style="cursor: pointer" id="quo_date" ondblclick="remove(this.id)">
                                <td style="width:60%;">Quotation Date</td>
                                <td style="width:40%;"><?php echo CommonHelper::changeDateFormat($Quotation->quotation_date);?></td>
                            </tr>
                            <tr style="cursor: pointer" id="revie" ondblclick="remove(this.id)">
                                <td>Revise Date</td>
                                <td>{{$Quotation->reevived_date}}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><?php echo $Quotation->subject?></p>
                        </div>
                    </div>
                </div>
                <?php //die();?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="buildyourform" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Serial No</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Height</th>
                                <th class="text-center">Width</th>
                                <th class="text-center">Uom</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Total Cost</th>
                            </tr>
                            </thead>
                            <tbody id="TrAppend">
                            <?php
                            $Counter=1;
                            $total_cost=0;
                            foreach($QuotationData as $Filter):?>
                            <tr>
                                <td><?php echo $Counter++;?></td>
                                <td><?php $produc_data=  CommonHelper::get_product_name_by_id($Filter->product_id);
                                    echo $produc_data->p_name;
                                    ?></td>
                                <td><?php echo $Filter->description;?></td>
                                <td><?php echo $Filter->height;?></td>
                                <td><?php echo $Filter->width;?></td>
                                <td><?php echo CommonHelper::get_uom_name($Filter->uom_id) ;?></td>
                                <td><?php echo $Filter->qty;?></td>
                                <td><?php echo $Filter->rate;?></td>
                                <td><?php echo $Filter->total_cost;
                                    $total_cost+=$Filter->total_cost;?></td>
                            </tr>
                            <?php endforeach;?>
                            <tr style="background-color: darkgray">
                                <td class="text-center" colspan="8"><b>Total</b></td>
                                <td colspan=""><b>{{number_format($total_cost,2)}}</b></td>
                            </tr>

                            @if($Quotation->discount_percent>0)

                                <tr>
                                    <td class="text-center" colspan="8"><b>Discount</b></td>
                                    <td colspan=""><b>{{number_format($Quotation->discount_percent).'% ('.number_format($Quotation->discount_amount,2).')'}}</b></td>
                                </tr>
                                @endif

                            @if($Quotation->sales_tax_percent>0)

                                <tr>
                                    <td class="text-center" colspan="8"><b>Sales Tax</b></td>
                                    <td colspan=""><b>{{number_format($Quotation->sales_tax_percent).'% ('.number_format($Quotation->sales_tax_amount,2).')'}}</b></td>
                                </tr>
                            @endif
                            <?php $total=$total_cost-$Quotation->discount_amount;
                            $total=$total+$Quotation->sales_tax_amount;
                            ?>

                            <tr style="background-color: darkgray">
                                <td class="text-center" colspan="8"><b>Grand Total</b></td>
                                <td colspan=""><b>{{number_format($total,2)}}</b></td>
                            </tr>
                            </tbody>

                        </table>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="">Other Terms and Conditions</label>
                                    <p><?php echo $Quotation->other_terms_conditions?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div style="line-height:8px;">&nbsp;</div>

            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
            <div class="container-fluid">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                        <p id="id1"><b>{{ ucwords($Quotation->username) }} </b></p>
                        <h6 class="signature_bor">Prepared By: </h6>
                        <b>   <p> <input type="text" name="" id="1" onkeyup="InnPut(this.id)" style="border:none"> <?php  ?></p></b>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                        <p id="id2">&nbsp;&nbsp;</p>
                        <h6 class="signature_bor">Checked By:</h6>
                        <b>   <p> <input type="text" name="" id="2" onkeyup="InnPut(this.id)"  style="border:none"> <?php  ?></p></b>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                        <p id="id3"><?php if ($Quotation->approve_username==''): echo '&nbsp;&nbsp;'; else: echo $Quotation->approve_username; endif  ?></p>
                        <h6 class="signature_bor">Approved By:</h6>
                        <b>  <p> <input type="text" name="" id="3" onkeyup="InnPut(this.id)" style="border:none"> </p></b>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
<script !src="">
    function PrintFunc(Id) {

        $(".remHref").attr("href", "");
        $( ".qrCodeDiv" ).removeClass( "hidden" );
        var printContents = document.getElementById(Id).innerHTML;
        //alert(printContents);
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        //if(param3 == 0){
        location.reload();
        //}
    }

    function ApprovedQuotation(QuotationId){
        var m = '<?php echo $_GET['m'];?>';
        $('#showDetailModelOneParamerter').modal('hide');
        $('#Loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $('#ShowHide').css('display','none');
        $.ajax({
            url: '<?php echo url('/')?>/sdc/ApprovedQuotation',
            type: "GET",
            data: { QuotationId:QuotationId,m:m},
            success:function(data) {

                $('#'+data).append('');
                $('#'+data).html('<td  class="text-center"><span class="badge badge-success" style="background-color: #00c851 !important">Success</span></td>')
                setInterval(function() {
                    $('#Loader').html('');
                    $('#ShowHide').css('display', 'block');
                },2000);





            }
        });
    }

    function remove(id)
    {
        $("#"+id).fadeOut(1000);
    }
</script>
