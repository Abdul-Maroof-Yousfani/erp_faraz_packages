<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\FinanceHelper;
?>


<?php
if($Type == 1):
$PurchaseRequestPending = DB::Connection('mysql2')->table('demand')->where('status',1)->where('demand_status',1)->count();
$PurchaseRequestApproved = DB::Connection('mysql2')->table('demand')->where('status',1)->where('demand_status',2)->count();

$POPending = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->where('purchase_request_status',1)->count();
$POApproved = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->whereIn('purchase_request_status', array(2, 3))->count();
$POGrnCreated = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->where('purchase_request_status',3)->count();



$GrnPending = DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->where('grn_status',1)->count();
$GrnApproved = DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->whereIn('grn_status', array(2, 3))->count();
$GrnInvCreated = DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->where('grn_status',3)->count();


$TotalDebitNote = DB::Connection('mysql2')->table('purchase_return')->where('status',1)->count();

$PurInvPending = DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_no','!=','')->where('pv_status',1)->count();
$PurInvApproved = DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_no','!=','')->where('pv_status',2)->count();

$StkTranPending = DB::Connection('mysql2')->table('stock_transfer')->where('status',1)->where('tr_status',1)->count();
$StkTranApproved = DB::Connection('mysql2')->table('stock_transfer')->where('status',1)->where('tr_status',2)->count();



?>
<div id="printDemandVoucherList">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    <thead>
                                    <th class="text-center">Purchase Request</th>
                                    <th class="text-center">Purchase Order</th>
                                    <th class="text-center">Goods Receipt Notes</th>
                                    <th class="text-center">Debit Note</th>
                                    <th class="text-center">Purchase Invoice</th>
                                    <th class="text-center">Stock Transfer</th>
                                    </thead>

                                    <tbody>
                                        <tr class="">
                                            <td>
                                                <button id="BtnApprovedPr" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiList('pr','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i> </button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $PurchaseRequestApproved?></span>
                                            </td>
                                            <td>
                                                <button id="BtnApprovedPo" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiList('po','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $POApproved?></span>
                                            </td>

                                            <td>
                                                <button id="BtnApprovedGrn" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiList('grn','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $GrnApproved?></span>
                                            </td>
                                            <td class="text-center" rowspan="3">
                                                <button id="BtnTotalDebitNote" class="btn btn-xs btn-primary" onclick="getPendingApporvedMultiList('tdn','1')">Total Debit Note <i class="fa fa-book" aria-hidden="true"></i></button>
                                                <br><br> <span style="background-color: red; text-align: center; font-size: 20px;" class="badge text-right"><?php echo $TotalDebitNote?></span>
                                            </td>

                                            <td>
                                                <button id="BtnApprovedPi" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiList('pi','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $PurInvApproved?></span>
                                            </td>
                                            <td>
                                                <button id="BtnApprovedSt" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiList('st','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $StkTranApproved?></span>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td>
                                                <button id="BtnPendingPr" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiList('pr','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $PurchaseRequestPending?></span>
                                            </td>
                                            <td>
                                                <button id="BtnPendingPo" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiList('po','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $POPending?></span>
                                            </td>


                                            <td>
                                                <button id="BtnPendingGrn" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiList('grn','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $GrnPending?></span>
                                            </td>
                                            <td>
                                                <button id="BtnPendingPi" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiList('pi','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $PurInvPending?></span>
                                            </td>
                                            <td>
                                                <button id="BtnPendingSt" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiList('st','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                                <span style="background-color: red; float: right;" class="badge text-right"><?php echo $StkTranPending?></span>
                                            </td>
                                        </tr>
                                            <tr>
                                                <td style="background-color: #ccc;"></td>
                                                <td>
                                                    <button id="BtnCreatedGrnPo" class="btn btn-xs btn-info" onclick="getPendingApporvedMultiList('po','3')">Grn Created <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                    <span style="background-color: red; float: right;" class="badge text-right"><?php echo $POGrnCreated?></span>
                                                </td>
                                                <td>
                                                    <button id="BtnCreatedInvoiceGrn" class="btn btn-xs btn-info" onclick="getPendingApporvedMultiList('grn','3')">Invoice Created <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                    <span style="background-color: red; float: right;" class="badge text-right"><?php echo $GrnInvCreated?></span>
                                                </td>
                                                <td colspan="2" style="background-color: #ccc;"></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AjaxDataHere">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script !src="">
    function getPendingApporvedMultiList(tab,contion)
    {
        var m = '<?php echo $m?>';

        $('#AjaxDataHere').html('<div class="loader"></div>');

        $.ajax({
            url: '/pdc/getPendingApporvedMultiList',
            type: 'Get',
            data: {tab: tab,contion:contion,m:m},

            success: function (response)
            {
                $('#AjaxDataHere').html(response);
            }
        });
    }
</script>
<?php elseif($Type == 2):?>

<?php

$sale_order = DB::Connection('mysql2')->table('sales_order')->where('status',1)->get();
$open=0;
$parttial=0;
$complete=0;
foreach($sale_order as $row):
    $customer=CommonHelper::byers_name($row->buyers_id);
    $data=SalesHelper::get_so_amount($row->id);
    $dn_data=SalesHelper::get_dn_amount_by_so_id($row->id);
    $dn_qty=0;
    if (!empty($dn_data->qty)):
        $dn_qty=$dn_data->qty;
    endif;
    $status='';
    $diffrence=round($data->qty-$dn_qty);
    $status='all';
    if ($dn_qty==''):
        $open++;

    elseif($dn_qty!='' && $diffrence!=0):

        $parttial++;

    elseif($diffrence==0):
        $complete++;
    endif;

endforeach;

?>

<?php
$opendn=0;
$parttialdn=0;
$completedn=0;
$delivery_note = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->get();
foreach($delivery_note as $row):

$data1=SalesHelper::get_total_amount_for_dn_by_id($row->id);
$status='';
$diffrence=round($data1->amount-$data1->dn_amount);

if ($data1->dn_amount==''):
    $opendn++;
elseif($data1->dn_amount!='' && $diffrence!=0):
    $parttialdn++;
elseif($diffrence==0):
    $completedn++;
endif;
endforeach;
$TotalSti = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->count();
$SalesReceiptPending = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',1)->where('sales',1)->count();
$SalesReceiptApproved = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',2)->where('sales',1)->count();
$TotalSalesReturn = DB::Connection('mysql2')->table('credit_note')->where('status',1)->count();

?>
<div id="printDemandVoucherList">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">Sales Order</th>
                                    <th class="text-center">Delivery Note</th>
                                    <th class="text-center">Sales Tax Invoice</th>
                                    <th class="text-center">Receipt Voucher</th>
                                    <th class="text-center">Sales Return</th>
                                    </thead>

                                    <tbody>
                                    <tr class="">
                                        <td>
                                            <button id="BtnSalesOrderOpen" class="btn btn-xs btn-info" onclick="getPendingApporvedMultiListForSales('so','1')">Total Open <i class="fa fa-folder-open-o" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $open?></span>
                                        </td>
                                        <td>
                                            <button id="BtnDNOpen" class="btn btn-xs btn-info" onclick="getPendingApporvedMultiListForSales('dn','1')">Total Open <i class="fa fa-folder-open-o" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $opendn?></span>
                                        </td>
                                        <td>
                                            <button id="BtnSalesTaxInvoice" class="btn btn-xs btn-primary" onclick="getPendingApporvedMultiListForSales('sti','1')">Sales Tax Invoice <i class="fa fa-file-text-o" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $TotalSti?></span>
                                        </td>
                                        <td>
                                            <button id="BtnSalesReceiptApproved" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForSales('srv','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $SalesReceiptApproved?></span>
                                        </td>
                                        <td>
                                            <button id="BtnSalesReturn" class="btn btn-xs btn-primary" onclick="getPendingApporvedMultiListForSales('srt','1')">Sales Return <i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $TotalSalesReturn?></span>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td>
                                            <button id="BtnSalesOrderPartial" class="btn btn-xs btn-danger" onclick="getPendingApporvedMultiListForSales('so','2')">Total Partial <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $parttial?></span>
                                        </td>
                                        <td>
                                            <button id="BtnDNPartial" class="btn btn-xs btn-danger" onclick="getPendingApporvedMultiListForSales('dn','2')">Total Partial <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $parttialdn?></span>
                                        </td>
                                        <td rowspan="2" style="background-color: #ccc;"></td>
                                        <td>
                                            <button id="BtnSalesReceiptPending" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiListForSales('srv','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $SalesReceiptPending?></span>
                                        </td>
                                        <td rowspan="2" style="background-color: #ccc;"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button id="BtnSalesOrderCompelete" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForSales('so','3')">Total Complete <i class="fa fa-tag" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $complete?></span>
                                        </td>
                                        <td>
                                            <button id="BtnDNOComplete" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForSales('dn','3')">Total Complete <i class="fa fa-tag" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $completedn?></span>
                                        </td>
                                        <td style="background-color: #ccc;"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AjaxDataHereForSales">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script !src="">

    function getPendingApporvedMultiListForSales(tab,contion)
    {
        var m = '<?php echo $m?>';

        $('#AjaxDataHereForSales').html('<div class="loader"></div>');

        $.ajax({
            url: '/pdc/getPendingApporvedMultiListForSales',
            type: 'Get',
            data: {tab: tab,contion:contion,m:m},

            success: function (response)
            {
                $('#AjaxDataHereForSales').html(response);
            }
        });
    }
</script>
<?php elseif($Type == 3):?>

<?php
$JvPending = DB::Connection('mysql2')->table('new_jvs')->where('status',1)->where('jv_status',1)->count();
$JvApproved = DB::Connection('mysql2')->table('new_jvs')->where('status',1)->where('jv_status',2)->count();

$BpvPending = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',1)->where('payment_type',1)->count();
$BpvApproved = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',2)->where('payment_type',1)->count();

$CpvPending = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',1)->where('payment_type',2)->count();
$CpvApproved = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',2)->where('payment_type',2)->count();

$BrvPending = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',1)->where('rv_type',1)->where('sales','!=',1)->count();
$BrvApproved = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',2)->where('rv_type',1)->where('sales','!=',1)->count();

$CrvPending = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',1)->where('rv_type',2)->where('sales','!=',1)->count();
$CrvApproved = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',2)->where('rv_type',2)->where('sales','!=',1)->count();


?>
<div id="printDemandVoucherList">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">Journal Voucher</th>
                                    <th class="text-center">Bank Payment Voucher</th>
                                    <th class="text-center">Cash Payment Voucher</th>
                                    <th class="text-center">Bank Receipt Voucher</th>
                                    <th class="text-center">Cash Receipt Voucher</th>
                                    </thead>

                                    <tbody>
                                    <tr class="">
                                        <td>
                                            <button id="BtnJvApproved" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForFinance('jv','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $JvApproved?></span>
                                        </td>
                                        <td>
                                            <button id="BtnBpvApproved" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForFinance('bpv','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $BpvApproved?></span>
                                        </td>
                                        <td>
                                            <button id="BtnCpvApproved" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForFinance('cpv','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $CpvApproved?></span>
                                        </td>

                                        <td>
                                            <button id="BtnBrvApproved" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForFinance('brv','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $BrvApproved?></span>
                                        </td>
                                        <td>
                                            <button id="BtnCrvApproved" class="btn btn-xs btn-success" onclick="getPendingApporvedMultiListForFinance('crv','2')">Total Approved <i class="fa fa-check" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $CrvApproved?></span>
                                        </td>

                                    </tr>
                                    <tr class="">
                                        <td>
                                            <button id="BtnJvPending" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiListForFinance('jv','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $JvPending?></span>
                                        </td>
                                        <td>
                                            <button id="BtnBpvPending" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiListForFinance('bpv','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $BpvPending?></span>
                                        </td>
                                        <td>
                                            <button id="BtnCpvPending" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiListForFinance('cpv','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $CpvPending?></span>
                                        </td>
                                        <td>
                                            <button id="BtnBrvPending" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiListForFinance('brv','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $BrvPending?></span>
                                        </td>
                                        <td>
                                            <button id="BtnCrvPending" class="btn btn-xs btn-warning" onclick="getPendingApporvedMultiListForFinance('crv','1')">Total Pending <i class="fa fa-spinner" aria-hidden="true"></i></button>
                                            <span style="background-color: red; float: right;" class="badge text-right"><?php echo $CrvPending?></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if(Session::get('run_company') == 1):?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                    <th class="text-center">Sr No</th>
                                    <th class="text-center">Account ID</th>
                                    <th class="text-center">Account Code</th>
                                    <th class="text-center">Account Name</th>
                                    <th class="text-center">Approved Amount</th>
                                    <th class="text-center">Unapproved Amount</th>
                                    {{--<th class="text-center">JV DR</th>--}}
                                    {{--<th class="text-center">JV CR</th>--}}
                                    {{--<th class="text-center">PV DR</th>--}}
                                    {{--<th class="text-center">PV CR</th>--}}
                                    {{--<th class="text-center">RV DR </th>--}}
                                    {{--<th class="text-center">RV CR </th>--}}
                                    </thead>
                                    <tbody>
                                        <?php
                                        $Counter=1;

                                        //  $CodeArray = ['1-2-4-1','1-2-4-2','1-2-4-3-1-1','1-2-4-3-1-2','1-2-4-3-3-1','1-2-6','1-2-7','1-2-9','1-2-3-1','2-2-1'];
                                         $CodeArray = [];
                                         $AccYear = ReuseableCode::get_account_year_from_to(Session::get('run_company'));


                                         foreach($CodeArray as $Fil):
                                         $account = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('code',$Fil)->select('id','name')->first();




                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $Counter++;?></td>
                                            <td><?php echo $account->id?></td>
                                            <td><?php echo $Fil?></td>
                                            <td><?php echo $account->name?></td>
                                            <td><?php echo number_format(CommonHelper::get_parent_and_account_amount('',$AccYear[0],$AccYear[1],$Fil,0,1,0),2);?></td>
                                            <td><?php echo   number_format(FinanceHelper::un_approved_amount($account->id),2);  ?></td>



                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AjaxDataHereForFinance">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>
<script !src="">

    function getPendingApporvedMultiListForFinance(tab,contion)
    {
        var m = '<?php echo $m?>';

        $('#AjaxDataHereForFinance').html('<div class="loader"></div>');

        $.ajax({
            url: '/pdc/getPendingApporvedMultiListForFinance',
            type: 'Get',
            data: {tab: tab,contion:contion,m:m},

            success: function (response)
            {
                $('#AjaxDataHereForFinance').html(response);
            }
        });
    }
</script>
<?php elseif($Type == 4):?>
<div id="printDemandVoucherList">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background: #ccc;"><h2>Inventory</h2></div>
                    </div>
                    <div class="row">
                        {{--Top Five Vendor By Total Purchase Start--}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    <thead>
                                    <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Vendor By Total Purchase Share</strong></th>
                                    </thead>
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Vendor</th>
                                    <th class="text-center">Revenue</th>
                                    <th class="text-center">Percent Share</th>
                                    </thead>
                                    <tbody>
                                    <?php for ($x = 1; $x <= 5; $x++):?>
                                    <tr class="text-center">
                                        <td><?php echo $x;?></td>
                                        <td></td>
                                        <td>0</td>
                                        <td>%</td>
                                    </tr>
                                    <?php endfor;?>
                                    <tr class="text-center">
                                        <td></td>
                                        <td>Total Amount</td>
                                        <td>0</td>
                                        <td>100%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--Top Five Vendor By Total Purchase End--}}
                        {{--Vendor Ageing Start Here--}}


                        <?php

                        $Clause = '';

                        $Tot_1_30End = 0;
                        $Tot_31_60End = 0;
                        $Tot_61_90End = 0;
                        $Tot_91_180End = 0;
                        $Tot_180_1000End = 0;
                        $TotOverAllEnd = 0;
                        $TotNotYetDueEnd = 0;
                        $company_data= ReuseableCode::get_account_year_from_to(Session::get('run_company'));
                        $from=$company_data[0];
                        $to = date('Y-m-d');
                        $Clause = '';

                        $Supp = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from supplier a
                                          INNER JOIN new_purchase_voucher b ON b.supplier = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'
                                          and(b.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)
                                          GROUP BY b.supplier');
                        $MainCount =  count($Supp);
                        $VendorCounter=1;
                        $main_count=1;

                        foreach($Supp as $Sfil):

                            $vendor_data=DB::Connection('mysql2')->select('select a.id,a.due_date,a.pv_no,a.pv_date,a.slip_no,(sum(b.net_amount)+a.sales_tax_amount)total,a.grn_id
                from new_purchase_voucher a
                inner join
                new_purchase_voucher_data b
                on
                a.id=b.master_id

                where a.status=1
               and(a.pv_date between "'.$from.'" and "'.$to.'" or grn_id=0)

                and a.supplier="'.$Sfil->id.'"
                group by a.id');
                            ?>

                            <?php
                            $TotInvoiceAmount = 0;
                            $TotReturnAmount = 0;
                            $TotPaidAmount = 0;
                            $TotBalance = 0;
                            $Tot_1_30 = 0;
                            $Tot_31_60 = 0;
                            $Tot_61_90 = 0;
                            $Tot_91_180 = 0;
                            $Tot_180_1000 = 0;
                            $TotOverAll = 0;
                            $TotNotYet = 0;

                            $amount=0;
                            foreach($vendor_data as $fil):

                                $no=0;
                                $one=0;
                                $two=0;
                                $three=0;
                                $four=0;
                                $five=0;
                                $InvoiceAmount = $fil->total;

                                $PaidAmount = CommonHelper::PaymentPurchaseAmountCheck_aging($fil->id,$from,$to);
                                $return_amount=ReuseableCode::return_amount_by_date($fil->grn_id,2,$from,$to);
                                $BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;


                                $diffss = strtotime($fil->due_date) - strtotime($fil->pv_date);

                                $diffss = abs(round($diffss / 86400));



                                $date1_ts = strtotime($fil->pv_date.'+'.$diffss.'day');
                                $date2_ts = strtotime($to);
                                $diff = $date2_ts - $date1_ts;
                                $NoOfDays = round($diff / 86400);
                                if($BalanceAmount > 0):
                                    if($NoOfDays <= 0){$TotNotYet+=$BalanceAmount; };
                                    if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount; $one=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(31,60))){  $Tot_31_60+=$BalanceAmount; $two=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(61,90))){  $Tot_61_90+=$BalanceAmount; $three=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(91,180))){  $Tot_91_180+=$BalanceAmount; $four=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(181,10000))){  $Tot_180_1000+=$BalanceAmount; $five=$BalanceAmount;}
                                    $TotOverAll+=$BalanceAmount;
                                    ?>

<?php
                                endif;
                            endforeach;?>
                            <?php if($TotOverAll > 0):
                            $TotNotYetDueEnd+=$TotNotYet;
                            $Tot_1_30End+=$Tot_1_30;
                            $Tot_31_60End+=$Tot_31_60;
                            $Tot_61_90End+=$Tot_61_90;
                            $Tot_91_180End+=$Tot_91_180;
                            $Tot_180_1000End+=$Tot_180_1000;
                            $TotOverAllEnd+=$TotOverAll;

                        endif;
                        endforeach;?>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    {{--<thead>--}}
                                    {{--<th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Profit Margin</strong></th>--}}
                                    {{--</thead>--}}
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Vendor Ageing</th>
                                    <th class="text-center">Amount</th>
                                    </thead>
                                    <tbody>
                                    <tr class="text-center">
                                        <td>1</td>
                                        <td>1 To 30</td>
                                        <td><?php echo number_format($Tot_1_30End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>2</td>
                                        <td>31 To 60</td>
                                        <td><?php echo number_format($Tot_31_60End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>3</td>
                                        <td>61 To 90</td>
                                        <td><?php echo number_format($Tot_61_90End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>4</td>
                                        <td>91 To 180</td>
                                        <td><?php echo number_format($Tot_91_180End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>5</td>
                                        <td>More Than 180</td>
                                        <td><?php echo number_format($Tot_180_1000End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>6</td>
                                        <td>Not Yet Due	</td>
                                        <td><?php echo number_format($TotNotYetDueEnd,2)?></td>

                                    </tr>

                                    <tr class="text-center">
                                        <td></td>
                                        <td>Total Amount</td>
                                        <td><?php echo number_format($TotOverAllEnd,2)?></td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--Vendor Ageing End Here--}}
                    </div>

                    <div class="row">
                        {{--Top Ten Inventory Items By Volume Start--}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    <thead>
                                    <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Ten Inventory Itemsby Volume</strong></th>
                                    </thead>
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Vendor</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Percent Share</th>
                                    </thead>
                                    <tbody>
                                    <?php for ($x = 1; $x <= 10; $x++):?>
                                    <tr class="text-center">
                                        <td><?php echo $x;?></td>
                                        <td></td>
                                        <td>0</td>
                                        <td>%</td>
                                    </tr>
                                    <?php endfor;?>
                                    <tr class="text-center">
                                        <td></td>
                                        <td>Total Amount</td>
                                        <td>0</td>
                                        <td>100%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--Top Ten Inventory Items By Volume End--}}
                        {{--Top Ten Inventory Items with High Profit Yield Start--}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    <thead>
                                    <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Ten Inventory Items with High Profit Yield</strong></th>
                                    </thead>
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Vendor</th>
                                    <th class="text-center">Revenue</th>
                                    <th class="text-center">Percent Share</th>
                                    </thead>
                                    <tbody>
                                    <?php for ($x = 1; $x <= 10; $x++):?>
                                    <tr class="text-center">
                                        <td><?php echo $x;?></td>
                                        <td></td>
                                        <td>0</td>
                                        <td>%</td>
                                    </tr>
                                    <?php endfor;?>
                                    <tr class="text-center">
                                        <td></td>
                                        <td>Total Amount</td>
                                        <td>0</td>
                                        <td>100%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--Top Ten Inventory Items with High Profit Yield End--}}
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background: #ccc;"  ><h2>Sales</h2></div>
                    </div>
                    <div class="row">
                        {{--Top Five Customer Revenue Start--}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    <thead>
                                    <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Revenue Share</strong></th>
                                    </thead>
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Revenue</th>
                                    <th class="text-center">Percent Share</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $Dta = DB::Connection('mysql2')->select('SELECT b.buyers_id,SUM(a.amount) am  FROM `sales_tax_invoice_data` a
INNER JOIN sales_tax_invoice b ON b.id = a.master_id
WHERE b.status = 1
AND a.amount > 0
GROUP BY b.buyers_id
order by am DESC
LIMIT 0,5');
                                            $Counter = 1;
                                            $TotAmount = 0;
                                    foreach($Dta as $ff):?>
                                    <tr class="text-center">
                                        <td><?php echo $Counter++;?></td>
                                        <td><?php echo CommonHelper::byers_name($ff->buyers_id)->name; ?></td>
                                        <td><?php echo number_format($ff->am); $TotAmount +=$ff->am;?></td>
                                        <td>%</td>
                                    </tr>
                                    <?php endforeach;?>
                                    <tr class="text-center">
                                        <td></td>
                                        <td>Total Amount</td>
                                        <td><?php echo number_format($TotAmount,2);?></td>
                                        <td>100%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--Top Five Customer Revenue Ent--}}
                        {{--Top Five Customer Profit Margin Start--}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    <thead>
                                    <th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Profit</strong></th>
                                    </thead>
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Profit</th>
                                    <th class="text-center">Percent Share</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $custome=[];
                                    $data=DB::Connection('mysql2')->select('select a.net,b.name,a.cost,a.gross,b.id as customer_id from si_criteria as a
                                     left join
                                     customers b
                                     on
                                     a.buyers=b.id
                                     order by net desc limit 5');

                                    $total=DB::Connection('mysql2')->selectOne('select sum(net)net from si_criteria')->net;

                                    $count=1;
                                    $total_amount=0;
                                    ?>
                                    @foreach($data as $row)
                                    <tr class="text-center">
                                        <td><?php echo $count++;?></td>
                                        <td>{{$row->name}}</td>
                                        <td>{{number_format($row->net,2)}}</td>
                                        <td title="{{number_format($total,2)}}">{{number_format(($row->net/$total)*100).'%'}}</td>

                                        <?php
                                        $customer[]=$row->customer_id;
                                        $total_amount+=$row->net;
                                        ?>
                                        @endforeach
                                    </tr>
                        <?php
                        $customer=implode(',',$customer);
                        $others=DB::Connection('mysql2')->selectOne('select sum(net)net from si_criteria where buyers not in  ('.$customer.')')->net; ?>
                                    <tr class="text-center">
                                        <td>{{$count++}}</td>
                                        <td>Others</td>
                                        <td>{{number_format($others,2)}}</td>
                                        <td></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="2">Total Amount</td>

                                        <?php $net=$total_amount+$others; ?>
                                        <td>{{number_format($net,2)}}</td>

                                        <td></td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--Top Five Customer Profit Margin Ent--}}
                    </div>
                    <div class="row">
                        {{--Customer Ageing Start Here--}}
                        <?php


                        $TotInvoiceAmountEnd = 0;
                        $TotReturnAmountEnd = 0;
                        $TotPaidAmountEnd = 0;
                        $TotBalanceEnd = 0;
                        $total_not_yet_due_end=0;
                        $Tot_1_30End = 0;
                        $Tot_31_60End = 0;
                        $Tot_61_90End = 0;
                        $Tot_91_180End = 0;
                        $Tot_180_1000End = 0;
                        $TotOverAllEnd = 0;
                        $Clause = '';

                        $company_data= ReuseableCode::get_account_year_from_to(Session::get('run_company'));
                        $from=$company_data[0];
                        $as_on=date('Y-m-d');

                        $Clause = '';
                        $Cust = DB::Connection('mysql2')->select('select a.id,a.name,a.acc_id from customers a
                                          INNER JOIN sales_tax_invoice b ON b.buyers_id = a.id
                                          WHERE b.status = 1
                                          '.$Clause.'
                                          and (b.gi_date between "'.$from.'" and "'.$as_on.'" or b.so_type=1)
                                          GROUP BY b.buyers_id');
                        $MainCount =  count($Cust);
                        $BuyerCounter =1;
                        $count=1;
                        ?>
                        <?php
                        foreach($Cust as $Cfil):
                            $vendor_data=DB::Connection('mysql2')->select('select a.id,a.model_terms_of_payment,a.due_date,a.gi_no,a.gi_date,(sum(b.amount)+a.sales_tax)total
                from sales_tax_invoice a
                inner join
                sales_tax_invoice_data b
                on
                a.id=b.master_id

                where a.status=1
                and (a.gi_date between "'.$from.'" and "'.$as_on.'" or b.so_type=1)
                and a.buyers_id  = '.$Cfil->id.'
                group by a.id');



                            $debit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=1 and acc_id="'.$Cfil->acc_id.'"')->amount;
                            $credit=   DB::Connection('mysql2')->selectOne('select sum(amount)amount from transactions where status=1 and debit_credit=0 and acc_id="'.$Cfil->acc_id.'"')->amount;

                            $amount=$debit-$credit;
                            ?>

                            <?php
                            $TotInvoiceAmount = 0;
                            $TotReturnAmount = 0;
                            $TotPaidAmount = 0;
                            $TotBalance = 0;
                            $total_not_yet_due=0;
                            $Tot_1_30 = 0;
                            $Tot_31_60 = 0;
                            $Tot_61_90 = 0;
                            $Tot_91_180 = 0;
                            $Tot_180_1000 = 0;
                            $TotOverAll = 0;
                            foreach($vendor_data as $fil):
                                $InvoiceAmount = $fil->total+SalesHelper::get_freight($fil->id);
                                $PaidAmount = CommonHelper::bearkup_receievd($fil->id,$from,$as_on);
                                $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice_by_date($fil->id,$from,$as_on);
                                $BalanceAmount = $InvoiceAmount-$return_amount-$PaidAmount;
                                $date1_ts = strtotime($fil->gi_date.'+'.$fil->model_terms_of_payment.'day');
                                $date2_ts = strtotime($as_on);
                                $diff = $date2_ts - $date1_ts;// - $date1_ts;
                                $NoOfDays = round($diff / 86400);
//$NoOfDays = str_replace("-","",$NoOfDays);
                                $MultiRvNO = DB::Connection('mysql2')->select('select rv_no from brige_table_sales_receipt where si_id = '.$fil->id.' group by rv_no ');

                                ?>
<?php
                                if ($BalanceAmount>0):
                                    if($NoOfDays <= 0){$total_not_yet_due+=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(1,30))){$Tot_1_30+=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(31,60))){ $Tot_31_60+=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(61,90))){ $Tot_61_90+=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(91,180))){$Tot_91_180+=$BalanceAmount;}
                                    if ( in_array($NoOfDays, range(181,10000))){$Tot_180_1000+=$BalanceAmount;}
                                    $TotOverAll+=$BalanceAmount;
                                    ?>

<?php  endif;
                            endforeach;
                            if($TotOverAll > 0):
                                ?>


                                <?php $total_not_yet_due_end+=$total_not_yet_due;?>
                                <?php $Tot_1_30End+=$Tot_1_30;?>
                                <?php $Tot_31_60End+=$Tot_31_60;?>
                                <?php $Tot_61_90End+=$Tot_61_90;?>
                                <?php  $Tot_91_180End+=$Tot_91_180;?>
                                <?php $Tot_180_1000End+=$Tot_180_1000;?>
                                <?php  $TotOverAllEnd+=$TotOverAll;?>



                            <?php
                            endif;
                        endforeach;?>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                            <div class="table-responsive" >
                                <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                    {{--<thead>--}}
                                    {{--<th class="text-center" colspan="4"><strong style="font-size: 18px;">Top Five Customers By Profit Margin</strong></th>--}}
                                    {{--</thead>--}}
                                    <thead>
                                    <th class="text-center">Sr No.</th>
                                    <th class="text-center">Customer Ageing</th>
                                    <th class="text-center">Amount</th>

                                    </thead>
                                    <tbody>

                                    <tr class="text-center">
                                        <td>1</td>
                                        <td>1 To 30</td>
                                        <td><?php echo number_format($Tot_1_30End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>2</td>
                                        <td>31 To 60</td>
                                        <td><?php echo number_format($Tot_31_60End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>3</td>
                                        <td>61 To 90</td>
                                        <td><?php echo number_format($Tot_61_90End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>4</td>
                                        <td>91 To 180</td>
                                        <td><?php echo number_format($Tot_91_180End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>5</td>
                                        <td>More Than 180</td>
                                        <td><?php echo number_format($Tot_180_1000End,2);?></td>

                                    </tr>
                                    <tr class="text-center">
                                        <td>6</td>
                                        <td>Not Yet Due	</td>
                                        <td><?php echo number_format($total_not_yet_due_end,2)?></td>

                                    </tr>

                                    <tr class="text-center">
                                        <td></td>
                                        <td>Total Amount</td>
                                        <td><?php echo number_format($TotOverAllEnd,2)?></td>

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
<?php else:?>
<?php endif;?>