<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$approved=ReuseableCode::check_rights(212);

$m = $_GET['m'];
$Checking = $_GET['id'];
$Checking = explode(',',$Checking);
if($Checking[0] == 'other')
{
    FinanceHelper::companyDatabaseConnection($m);
    $jvs = DB::table('new_purchase_voucher')->where('pv_no','=',$Checking[1])->first();
    FinanceHelper::reconnectMasterDatabase();
    $id = $jvs->id;
}
else{
    $id = $Checking[0];
}
$currentDate = date('Y-m-d');
FinanceHelper::companyDatabaseConnection($m);
$PurchaseVoucher = DB::table('new_purchase_voucher')->where('id','=',$id)->get();
FinanceHelper::reconnectMasterDatabase();
foreach ($PurchaseVoucher as $row) {
$username=$row->username;
$approve_1=$row->approved_user;
$approve_2=$row->approve_user_2;
$exp_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',1)->sum('net_amount');
$item_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',0)->sum('net_amount');

$Supplier = CommonHelper::get_single_row('supplier','id',$row->supplier);
?>
<div class="row headquid">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php if($row->pv_status ==1 || $row->pv_status ==3) :?>
            <?php if($approved == true):?>
                <button type="button" class="btn btn-xs btn-primary" onclick="ApproveVoucher('<?php echo $id?>')">Approve</button>
            <?php endif;?>
        <?php
        endif;
            //echo CommonHelper::displayPrintButtonInView('printPurchaseVoucherDetail','','1');

        //echo FinanceHelper::displayApproveDeleteRepostButton($m,$row->jv_status,$row->status,$row->jv_no,'jv_no','jv_status','status');?>
            <button class="btn btn-sm btn-primary" onclick="printViewTwo('printPurchaseVoucherDetail','','1')" style="">
                <span class="glyphicon glyphicon-print"> Print</span>
            </button>
            @if ($row->pv_status == 2 && $row->grn_no == 0)
                <button type="button" data-pvno="{{ $row->pv_no }}" class="btn btn-sm btn-warning reverse-direct-invoice" style="">
                    Reverse Direct Invoice
                </button>
            @endif
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printPurchaseVoucherDetail">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">Payable Voucher</h3>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:30%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>

                                    <tr>
                                        <td style="width:40%;">Vendor</td>
                                        <td style="width:60%;"><?php echo $Supplier->name;?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">Address</td>
                                        <td style="width:60%;"><?php echo $Supplier->address;?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">NTN</td>
                                        <td style="width:60%;"><?php echo $Supplier->ntn;?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%;">PV No.</td>
                                        <td style="width:60%;"><?php echo $row->pv_no;?></td>
                                    </tr>

                                    @if ($row->grn_no!=0)
                                    <tr>
                                        <td style="width:40%;">GRN No.</td>
                                        <td style="width:60%;"><?php echo $row->grn_no;?></td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                            <div style="width:30%; float:right;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                        <tr>
                                            <td>PV Date</td>
                                            <td><?php echo FinanceHelper::changeDateFormat($row->pv_date);?></td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Ref / Bill No.</td>
                                            <td style="width:60%;"><?php echo $row->slip_no;?></td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Bill Date</td>
                                            <td style="width:60%;"><?php  echo FinanceHelper::changeDateFormat($row->bill_date);?></td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Due Date</td>
                                            <td style="width:60%;"><?php  echo FinanceHelper::changeDateFormat($row->due_date);?></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table style=""  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px;">S.No</th>

                                            <th class="text-center">Account</th>
                                            <th class="text-center">Uom</th>
                                            <th class="text-center">No. of Bags</th>
                                            <th class="text-center">Qty (KG)</th>
                                            <th class="text-center">Qty (Pound)</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-center" colspan="8">Amount</th>
                                            <th class="text-center">EXP%</th>
                                            <th class="text-center">Additional Expenses</th>
                                            <th class="text-center">Discount Amount</th>
                                            <th class="text-center" colspan="7">Net Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    FinanceHelper::companyDatabaseConnection($m);
                                    $PurchaseVoucherData = DB::table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',0)->get();

                                    $costing_data=$PurchaseVoucherData;
                                    $type = 5;
                                    FinanceHelper::reconnectMasterDatabase();
                                    $counter = 1;
                                    $TotalBagQty=0;
                                    $TotalQty=0;
                                    $TotalLbsQty=0;
                                    $TotalAmount=0;
                                            $TotalAddional = 0;
                                            $count= count($PurchaseVoucherData);
                                        //    $exp_amount= $exp_amount / $count;;
                                    foreach ($PurchaseVoucherData as $row2) {


                                        if ($row->grn_no!=0):
                                  $grn_amount=  DB::Connection('mysql2')->table('grn_data')->where('id',$row2->grn_data_id)->select('net_amount')->value('net_amount');
                                    $LocationId =  DB::Connection('mysql2')->table('grn_data')->where('id',$row2->grn_data_id)->select('warehouse_id')->first();

                                            $row2->grn_data_id;
                                  $return_amount=  DB::Connection('mysql2')->table('purchase_return_data')->where('grn_data_id',$row2->grn_data_id)->where('status',1)
                                          ->sum('net_amount');
                                    endif;
                                            $pi_amount=$row2->net_amount;
                                            $item_amount_percent = ($pi_amount / $item_amount) * 100;
                                            $exp_amount_apply = ($exp_amount /100) * $item_amount_percent;

                                    ?>
                                    <tr class="text-center">
                                        <td class="text-center"><?php echo $counter++;?></td>

                                        <td title="{{$row2->sub_item}}">{{ CommonHelper::get_name_of_account_operat_by_id($row2->sub_item) }} </td>
                                        <td>
                                            <?php echo CommonHelper::get_uom($row2->sub_item);?>
                                        </td>
                                        <td><?php  echo $row2->bag_qty?></td>
                                        <td><?php  echo $row2->qty?></td>
                                        <td><?php  echo $row2->lbs_qty?></td>
                                        <td><?php  echo $row2->rate?></td>
                                        <td colspan="8"><?php  echo $row2->amount; ?></td>
                                        <td><?php  echo number_format($item_amount_percent,3); ?></td>
                                        <td>{{  number_format($exp_amount_apply,2) }}</td>
                                        <td><?php  echo $row2->discount_amount; ?></td>
                                        <td ><?php  echo number_format($row2->net_amount+$exp_amount_apply,2); $TotalAmount +=$row2->net_amount+$exp_amount_apply;?></td>

<?php
$TotalBagQty +=$row2->bag_qty;
$TotalQty +=$row2->qty;
$TotalLbsQty +=$row2->lbs_qty;
?>


                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="sf-table-total">
                                        <td colspan="3" class="text-center">
                                            <label for="field-1" class="sf-label"><b>Total</b></label>
                                        </td>
                                        <td class="text-center"><b><?php echo number_format($TotalBagQty,2)?></b></td>
                                        <td class="text-center"><b><?php echo number_format($TotalQty,2)?></b></td>
                                        <td class="text-center"><b><?php echo number_format($TotalLbsQty,2)?></b></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><b><?php echo number_format($TotalAmount,2)?></b></td>
                                        <input type="hidden" id="Total" value="<?php echo $TotalAmount?>">
                                    </tr>
                                    <tr class="sf-table-total">
                                        <td colspan="7" class="text-center">
                                            <label for="field-1" class="sf-label"><b>Total Payable</b></label>
                                        </td>
                                        <td class="text-center"><b id=""></b></td>
                                    </tr>
                                    <?php if($row->sales_tax_amount > 0):
                                    $Accounts = CommonHelper::get_single_row('accounts','id',$row->sales_tax_acc_id);
                                    ?>
                                    <tr class="sf-table-total">
                                        <td colspan="6" class="text-right">Sales Tax :</td>
                                        <td  class="text-right"><b><?php echo optional($Accounts)->name?></b></td>
                                        <td class="text-center"><b><?php echo number_format($row->sales_tax_amount,2)?></b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-center"><b>Total After Sales Tax<b></td>
                                        <td class="text-center"><b><?php echo number_format($row->sales_tax_amount+$TotalAmount,2)?></b></td>
                                    </tr>
                                    <?php endif;?>
                                    </tbody>
                                </table>

                                <p class="desc-box">{{ 'Description:'. $row->description }}</p>
                            </div>
                        </div>


                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width:50px;">S.No</th>
                                        <th class="text-center">Account</th>
                                        <th class="text-center">Amount</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                        <?php
                        $counter=1;
                        $aditional_exp = DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',1)->get(); ?>

                                    @foreach($aditional_exp as $row3)
                                    <tr>
                                         <td>{{$counter++}}</td>
                                        <td>{{CommonHelper::get_account_name($row3->category_id)}}</td>
                                        <td>{{number_format($row3->net_amount,2) }}<?php $TotalAddional+=$row3->net_amount;?></td>
                                    </tr>
                                    @endforeach
                                    <input type="hidden" id="TotalAddional" value="<?php echo $TotalAddional?>">
                                    </tbody>

                    </table>
                                </div>
                            </div>


                        <?php
                        FinanceHelper::companyDatabaseConnection($m);

                        $rvsDetail = DB::table('transactions')->where('voucher_no','=',$row->pv_no)->where('status',1)->orderby('debit_credit','1')->get();

                        $costing_data=$rvsDetail;
                        $type = 5;
                        FinanceHelper::reconnectMasterDatabase();
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
                                <td><?php echo FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);?></td>



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

                        <label class="check printHide">
                            Show Voucher
                            <input id="check"  type="checkbox" onclick="checkk()" class="check">
                        </label>

                        {{--@include('Finance.AjaxPages.view_costing_for_vouchers')--}}


                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Prepared By: </h6>
                                            <b>   <p><?php echo strtoupper($username);  ?></p></b>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Approved By:</h6>
                                            <b>   <p><?php echo strtoupper($approve_1) ?></p></b>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Approved By:</h6>
                                            <b>  <p><?php echo strtoupper($approve_2)?></p></b>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Recieved By</h6>

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
<?php }?>
<script !src="">
    $(document).ready(function(){
        $('.reverse-direct-invoice').on('click', function() {
            // alert($(this).data('pvno'));
            var m = {{ $_GET['m'] }}
            $.ajax({
            url: '<?php echo url('/')?>/pad/reverseDirectPurchaseInvoice',
            type: "GET",
            data: {
				m:m,
                pvno: $(this).data('pvno')
			},
            success:function(data) {
                console.log(data);
				if(data.status){
					alert(data.msg);
                    location.reload()
				}else{
					alert(data.msg);
				}
            }
        });
        })

        var Total = parseFloat($('#Total').val());
        var TotalAddional = parseFloat($('#TotalAddional').val());
        $('#TotalPayable').html(parseFloat(Total+TotalAddional).toFixed(2));
    });
    function ApproveVoucher(PvId){
        var m = '<?php echo $_GET['m'];?>';

        $('#Loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $('#ShowHide').css('display','none');
        $.ajax({
            url: '<?php echo url('/')?>/fdc/approvePurchaseVoucherDetail',
            type: "GET",
            data: { PvId:PvId,m:m},
            success:function(data) {
                $('#showDetailModelOneParamerter').modal('hide');
                $('#Append'+data).html('');
                $('#Append'+data).html('<span class="badge badge-success" style="background-color: #00c851 !important">Success</span>');
                $('#Loader').html('');
                $('#app'+PvId).html('Approved');
                $('.btn'+PvId).remove();
                $('#BtnEdit'+data).css('display','none');
                $('#ShowHide').css('display', 'block');
                location.reload();
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
