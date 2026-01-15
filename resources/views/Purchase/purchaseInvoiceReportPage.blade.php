<?php
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <style>
        .ApnaBorder{
            border-color: black !important;
            border: double;
        }
        .Chnage-bg{
            background-color: #ccc !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Purchase Invoice Report</span>
                    </div>

                </div>

                <?php
                $PurInvData = DB::Connection('mysql2')->select('select sum(b.net_amount) inv_amount,a.id,a.pv_no,a.pv_date,a.grn_no from new_purchase_voucher a
                                                  INNER JOIN new_purchase_voucher_data b ON b.master_id = a.id
                                                  WHERE a.grn_no != ""
                                                  AND a.status = 1
                                                  GROUP BY a.id
                                                  ');
                ?>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" id="PrintExport">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">PI No</th>
                                                    <th class="text-center">PI Date</th>
                                                    <th class="text-center">Grn No</th>
                                                    <th class="text-center">Purchase Invoice Amount</th>
                                                    <th class="text-center">Invoice Return Amount</th>
                                                    <th class="text-center">Paid Amount</th>
                                                </thead>
                                                <tbody id="viewRegionList">
                                                <?php
                                                $Counter = 1;
                                                        $TotPurInvAmount = 0;
                                                        $TotPurInvRetAmount = 0;
                                                        $TotPaidAmount = 0;
                                                foreach($PurInvData as $Fil):
                                                $ReturnAmount = DB::Connection('mysql2')->selectOne('select sum(b.net_amount) return_amount from purchase_return a
                                                                                    INNER JOIN purchase_return_data b ON b.master_id = a.id
                                                                                    WHERE a.grn_no = "'.$Fil->grn_no.'"
                                                                                    AND a.type = 2
                                                                                    GROUP BY a.id
                                                                                    ');
                                                 $PaidAmount = DB::Connection('mysql2')->selectOne('select sum(amount) paid_amount from new_purchase_voucher_payment
                                                                                                    WHERE new_purchase_voucher_id  = '.$Fil->id.'
                                                                                                    AND status = 1
                                                                                                    GROUP BY new_pv
                                                                                                    ');
                                                ?>
                                                    <tr class="text-center">
                                                        <td><?php echo $Counter++;?></td>
                                                        <td><?php echo strtoupper($Fil->pv_no)?></td>
                                                        <td><?php echo date_format(date_create($Fil->pv_date), 'd-m-Y')?></td>
                                                        <td><?php echo strtoupper($Fil->grn_no)?></td>
                                                        <td><?php echo number_format($Fil->inv_amount,2); $TotPurInvAmount+=$Fil->inv_amount?></td>
                                                        <td>
                                                            <?php if(!empty($ReturnAmount)): echo number_format($ReturnAmount->return_amount,2); $TotPurInvRetAmount+=$ReturnAmount->return_amount; else: echo 0; endif;?>
                                                        </td>
                                                        <td>
                                                            <?php if(!empty($PaidAmount)): echo number_format($PaidAmount->paid_amount,2); $TotPaidAmount+=$PaidAmount->paid_amount; else: echo 0; endif;?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;?>
                                                    <tr class="text-center">
                                                        <td colspan="4">TOTAL</td>
                                                        <td><?php echo number_format($TotPurInvAmount,2);?></td>
                                                        <td><?php echo number_format($TotPurInvRetAmount,2);?></td>
                                                        <td><?php echo number_format($TotPaidAmount,2);?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script !src="">
        $(document).ready(function(){
            $('#SupplierId').select2();
        });

        function GetAgingReportData(){

            var SupplierId = $('#SupplierId').val();
            var m = '<?php echo $_GET['m'];?>';
            if(SupplierId !="")
            {
                $('#GetDataAjax').html('<tr><td colspan="4"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/getAgingReportDataAjax',
                    type: "GET",
                    data:{m:m,SupplierId:SupplierId},
                    success:function(data) {
                        setTimeout(function(){
                            $('#GetDataAjax').html(data);
                        },1000);
                    }
                });
            }
            else
            {
                $('#SupplierError').html('<p class="text-danger">Please Select Supplier</p>');
            }

        }

    </script>
@endsection