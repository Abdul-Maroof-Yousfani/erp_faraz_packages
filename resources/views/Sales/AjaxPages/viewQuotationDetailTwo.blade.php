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
        <?php CommonHelper::displayPrintButtonInView('printCashSaleVoucherDetail','','1');?>
        <button type="button" class="btn btn-sm btn-primary" onclick="PrintFunc('printCashSaleVoucherDetail')">Print</button>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printCashSaleVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <img style="text-align: center; width: 45%" src="{{url('/storage/app/uploads/sn-logo.png')}}">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                    <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                    <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 signature_bor"></div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $client_data=CommonHelper::get_client_data_by_id($Quotation->client_id);
                        $client_name=$client_data->client_name;
                        $ntn=$client_data->ntn;
                        $strn=$client_data->strn;
                        $address=$client_data->address;
                        ?>

                        <p>{{$Quotation->quotation_to}}</p>
                        <p>{{$address}}</p>
                        <p><?php echo CommonHelper::changeDateFormat($Quotation->quotation_date);?></p>
                    </div>

                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Attention:<?php echo $Quotation->quotation_to?></label>
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 signature_bor"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="">S M Shah Jamal</label>
                                    <p>&emsp;&emsp; 10-C, Mezzanine Floor Street No. 5, Badar Commercial Area Phase V-Ext. Defence Housing Authority, Karachi.<br>
                                        &emsp;&emsp;&emsp;Phone# (92-21) 534-4584, 534-4586 Fax# (92-21) 534-4586, Email: info@signsnow.com.pk</p>

                                </div>
                            </div>
                        </div>
                        {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">--}}
                        {{--<div class="container-fluid">--}}
                        {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                        {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">--}}
                        {{--<h6 class="signature_bor">Prepared By: </h6>--}}
                        {{--<b>   <p></p></b>--}}
                        {{--</div>--}}
                        {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">--}}
                        {{--<h6 class="signature_bor">Checked By:</h6>--}}
                        {{--<b>   <p></p></b>--}}
                        {{--</div>--}}
                        {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">--}}
                        {{--<h6 class="signature_bor">Approved By:</h6>--}}
                        {{--<b>  <p></p></b>--}}
                        {{--</div>--}}

                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div style="line-height:8px;">&nbsp;</div>

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
</script>
