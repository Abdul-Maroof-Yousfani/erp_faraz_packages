<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$approve=ReuseableCode::check_rights(213);
$m=Session::get('run_company');
foreach (CommonHelper::internal_consumtion_list_by_id(Request::get('id')) as $row)
{
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if($approve)
            @if ($row->voucher_status==1)
                <button onclick="approve({{$row->id}})" type="button" id="approve" class="btn btn-success btn-xs">Approve</button>
            @endif
        @endif
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','LinkHide','1');?>
    </div>

</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">Internal Consumption</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Voucher No.</td>
                            <td class="text-center"><?php echo strtoupper($row->voucher_no);?></td>
                        </tr>
                        <tr>
                            <td>Voucher Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->voucher_date);?></td>
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
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Item Name</th>
                            <th class="text-center">Location From</th>
                            <th class="text-center">Transfer Qty</th>
                            <th class="text-center hide">Rate</th>
                            <th class="text-center hide">Amount</th>
                            <th class="text-center">Account Name</th>
                            <th class="text-center">Description</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $counter=1;
                        foreach (CommonHelper::internal_consumtion_data(Request::get('id')) as $row1){
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php echo $counter++;?>

                            </td>
                            <td  id="{{$row1->id}}" title="{{$row1->item_id}}">

                                <?php $accType = Auth::user()->acc_type;
                                if($accType == 'client'):
                                ?>
                                <a class="LinkHide" href="<?php echo url('/') ?>/store/fullstockReportView?pageType=&&parentCode=97&&m=<?php echo Session::get('run_company');?>&&sub_item_id=<?php echo $row1->item_id; ?>&&warehouse_id=<?php echo $row1->warehouse_from; ?>" target="_blank">
                                    <?php
                                    echo CommonHelper::get_item_name($row1->item_id);
                                    ?>
                                </a>
                                <?php else:?>
                                    <?php
                                    echo CommonHelper::get_item_name($row1->item_id);
                                    ?>
                                    <?php endif;?>


                            </td>
                            <td>
                                <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$row1->warehouse_from);?>
                            </td>
                            <td class="text-center"><?php echo number_format($row1->qty,2);?></td>
                            <td class="text-center hide"><?php echo number_format($row1->rate,2);?></td>
                            <td class="text-center hide"><?php echo number_format($row1->amount,2);?></td>
                            <td>
                                <?php echo CommonHelper::get_account_name($row1->acc_id);?>
                            </td>

                            <td><textarea readonly class="form-control">{{$row1->desc}}</textarea></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label class="check">
                        Show Voucher
                        <input id="check"  type="checkbox" onclick="checkk()" class="check">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php


                    $rvsDetail = DB::Connection('mysql2')->table('transactions')->where('voucher_no','=',$row->voucher_no)->where('status',1)->orderby('debit_credit','1')->get();


                    $counter = 1;
                    $g_t_debit = 0;
                    $g_t_credit = 0;

                    ?>

                    <table style="display: none;"  id=""  class="table table-bordered tra">
                        <tr class="">
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Account</th>




                            <th class="text-center" style="width:150px;">Debit</th>
                            <th class="text-center" style="width:150px;">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($rvsDetail as $row2) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $counter++;?></td>
                            <td><?php echo CommonHelper::get_account_name($row2->acc_id,$m);?></td>



                            <td class="debit_amount text-right">

                                <?php
                                if($row2->debit_credit == 1)
                                {
                                    $g_t_credit += $row2->amount;
                                    echo number_format($row2->amount,2);
                                }
                                ?>
                            </td>
                            <td class="credit_amount text-right">
                                <?php
                                if($row2->debit_credit == 0)
                                {
                                    $g_t_debit += $row2->amount;
                                    echo number_format($row2->amount,2);
                                }
                                ?>
                            </td>

                        </tr>
                        <?php
                        }
                        ?>
                        <tr class="sf-table-total">
                            <td colspan="2">
                                <label for="field-1" class="sf-label"><b>Total</b></label>
                            </td>
                            <td class="text-right"><b><?php echo number_format($g_t_credit,2);?></b></td>
                            <td class="text-right"><b><?php echo number_format($g_t_debit,2);?></b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div style="line-height:8px;">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h6>Remarks: <?php echo strtoupper($row->description); ?></h6>
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
                                <b>   <p><?php echo strtoupper($row->username);  ?></p></b>
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
                <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Demand Voucher Detail'))!!} ">
            </div>
            <!-->
        </div>
    </div>


    <?php }?>

    <?php echo Form::close();?>
</div>
<script type="text/javascript">

    function approve(id)
    {
        $('#approve').prop('disabled', true);
        $.ajax({
            url : '{{url('/stdc/internal_cosum')}}',
            type: "GET",
            data :{id:id},
            success:function(data){
                //alert(data); return false;
                if (data=='0')
                {
                    $('#showDetailModelOneParamerter').modal('toggle');
                    $('.'+id).text('Approve');
                }
                else
                {
                    alert(data);
                    $("#"+data).css("background-color", "red")
                }




            }
        });
    }
    function checkk()
    {

        if ($("#check").is(":checked"))
        {


            $('.tra').css('display','block');
        }

        else
        {
            $('.tra').css('display','none');
        }
    }
</script>

