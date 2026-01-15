<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$id = $_GET['id'];
$Master = DB::Connection('mysql2')->table('production_work_order')->where('status',1)->where('id',$id)->first();
$Detail = DB::Connection('mysql2')->table('production_work_order_data')->where('status',1)->where('master_id',$id)->get();
        //echo "<pre>";
        //print_r($Detail);
        //die();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInView('printCashPaymentVoucherDetail','','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="printCashPaymentVoucherDetail" class="well">
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
                            <h3 style="text-align: center;">Operation Detail</h3>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:30%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">Operation Name.</td>
                                        <td style="width:60%;"><?php echo strtoupper(CommonHelper::get_item_name($Master->finish_good_id));?></td>
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
                                        <th class="text-center">Machine Name</th>
                                        <th class="text-center">Capacity(%)</th>
                                        <th class="text-center">Qeue Time (Minutes)</th>
                                        <th class="text-center">Move Time (Minutes)</th>
                                        <th class="text-center">Wait Time (Minutes)</th>
                  

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($Detail as $Fil):
                                    $Machine = CommonHelper::get_single_row('production_machine','id',$Fil->machine_id);
                                    //$LabCat = CommonHelper::get_single_row('production_labour_category','id',$Fil->labour_category_id)
                                            $QueArray = explode(':',$Fil->que_time);
                                            $QueMinutes = $QueArray[0] * 60  + $QueArray[1];

                                            $MoveArray = explode(':',$Fil->move_time);
                                            $MoveMinutes = $MoveArray[0] * 60  + $MoveArray[1];

                                            $WaitArray = explode(':',$Fil->wait_time);
                                            $WaitMinutes = $WaitArray[0] * 60  + $WaitArray[1];
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $Machine->machine_name?></td>
                                        <td><?php echo $Fil->capacity?></td>
                                        <td title="Total Minutes = <?php echo $QueMinutes?>"><?php echo $Fil->que_time?></td>
                                        <td title="Total Minutes = <?php echo $MoveMinutes?>"><?php echo $Fil->move_time?></td>
                                        <td title="Total Minutes = <?php echo $WaitMinutes?>"><?php echo $Fil->wait_time?></td>

                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{--<div class="row">--}}
                                {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                                    {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">--}}
                                            {{--<h6 class="signature_bor">Prepared By: </h6>--}}
                                            {{--<b>   <p>< ?php echo strtoupper($username);  ?></p></b>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">--}}
                                            {{--<h6 class="signature_bor">Verified By:</h6>--}}
                                            {{--<b>   <p>< ?php  ?></p></b>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">--}}
                                            {{--<h6 class="signature_bor">Approved By:</h6>--}}
                                            {{--<b><p>< ?php echo strtoupper($approved_user)?></p></b>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">--}}
                                            {{--<h6 class="signature_bor">Recieved By</h6>--}}

                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($_GET['type'] == 'print'):?>
<script !src="">
    $(document).ready(function(){
//        printView('printCashPaymentVoucherDetail','','1');
//        function printView(param1,param2,param3) {


        $( ".qrCodeDiv" ).removeClass( "hidden" );
//            if(param2!="")
//            {
//                $('.'+param2).prop('href','');
//            }
        $('.printHide').css('display','none');
        var printContents = document.getElementById('printCashPaymentVoucherDetail').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        //if(param3 == 0){
        //location.reload();

        //}
//        }
    });
</script>
<?php endif;?>
