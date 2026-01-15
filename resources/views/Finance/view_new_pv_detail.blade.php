<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;


$m = $_GET['m'];
$Checking = $_GET['id'];
$Checking = explode(',',$Checking);
if($Checking[0] == 'other')
{
    FinanceHelper::companyDatabaseConnection($m);
    $rvs = DB::table('new_rvs')->where('rv_no','=',$Checking[1])->first();
    FinanceHelper::reconnectMasterDatabase();
    $id = $rvs->id;
}
else{
    $id = $Checking[0];
}
$Type = $_GET['type'];
$currentDate = date('Y-m-d');

$Master = DB::Connection('mysql2')->table('new_pvv')->where('id','=',$id)->get();

foreach ($Master as $row) {
//$username=$row->username;
//$approved_user=$row->approved_user;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <button class="btn btn-xs btn-info" onclick="printView('printBankPaymentVoucherDetail','','1')" style="">
            <span class="glyphicon glyphicon-print"> Print</span>
        </button>

        @if($row->pv_status==1)
        <button id="approved" onclick="approve('{{$id}}')" type="button" class="btn btn-success">Approve</button>
            @endif
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printBankPaymentVoucherDetail">
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
                            <h3 style="text-align: center;">Pv Detail</h3>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:40%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">PV No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($row->pv_no);?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">Pv Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($row->pv_date);?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">Supplier</td>
                                        <td><?php echo CommonHelper::get_supplier_name($row->supplier_id);?></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div style="width:40%; float:right;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td>Supplier Invoice No</td>
                                        <td><?php echo $row->supplier_invoice_no;?></td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Invoice Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($row->supplier_invoice_date);?></td>
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
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Uom</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $Detail = DB::Connection('mysql2')->table('new_pvv_data')->where('master_id','=',$id)->get();

                                    $counter = 1;
                                    $TotalAmount = 0;

                                    foreach ($Detail as $row2) {
                                    ?>
                                    <tr class="text-center">
                                        <td class="text-center"><?php echo $counter++;?></td>
                                        <td><?php echo $row2->description?></td>
                                        <td><?php echo CommonHelper::get_uom_name($row2->uom_id);?></td>
                                        <td><?php echo number_format($row2->qty,2)?></td>
                                        <td><?php echo number_format($row2->rate,2)?></td>
                                        <td><?php echo number_format($row2->amount,2); $TotalAmount+=$row2->amount;?></td>

                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="text-center">
                                        <td colspan="3"></td>
                                        <td colspan="2"><b style="font-size: 16px;">TOTAL</b></td>
                                        <td><?php echo number_format($TotalAmount,2)?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="3"></td>
                                        <td colspan="2">
                                            <b style="font-size: 16px;"><?php echo CommonHelper::get_account_name($row->sales_tax_acc_id);?></b>
                                        </td>
                                        <td><?php echo number_format($row->sales_tax_amount,2)?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="3"></td>
                                        <td colspan="2"><b style="font-size: 16px;">AFTER TAX TOTAL</b></td>
                                        <td><?php echo number_format($TotalAmount+$row->sales_tax_amount);?></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>


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
                           $data=  DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$row->pv_no)->get();
                            $total_debit=0;
                            $total_credit=0;
                            foreach ($data as $row1):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td><?php echo FinanceHelper::getAccountNameByAccId($row1->acc_id,Session::get('run_company'));?></td>
                                <td class="debit_amount text-right">@if($row1->debit_credit == 1){{number_format($row1->amount,2)}} @php $total_debit+=$row1->amount  @endphp  @endif </td>
                                <td class="debit_amount text-right">@if($row1->debit_credit == 0){{number_format($row1->amount,2)}} @php $total_credit+=$row1->amount  @endphp  @endif </td>

                            </tr>
                            <?php endforeach;
                            ?>
                            <tr class="sf-table-total">
                                <td colspan="2">
                                    <label for="field-1" class="sf-label"><b>Total</b></label>
                                </td>
                                <td class="text-right"><b><?php echo number_format($total_debit,2);?></b></td>
                                <td class="text-right"><b><?php echo number_format($total_credit,2);?></b></td>
                            </tr>
                            </tbody>
                        </table>

                        <label class="check printHide">
                            Show Voucher
                            <input id="check"  type="checkbox" onclick="checkk()" class="check">
                        </label>


                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p><?php echo $row->description?></p>
                        </div>

                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Prepared By: </h6>
                                            <b>   <p><?php echo strtoupper($row->username);  ?></p></b>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Verified By:</h6>
                                            <b>   <p><?php  ?></p></b>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Approved By:</h6>
                                            <b>  <p><?php //echo strtoupper($approved_user)?></p></b>
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


<script>
    function approve(id)
    {


        $('#approved').prop('disabled', true);


    $.ajax({
        url: '/approve_new_pv',
        type: 'Get',
        data: {id:id},

        success: function (response)
        {
            $('#status'+id).html('Approved')
            $('#showDetailModelOneParamerter').modal('hide');
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
<?php }?>
