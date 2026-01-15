<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$m = $_GET['m'];
$makeGetValue = explode('*',$_GET['grnNo']);
$grnNo = $makeGetValue[0];
$grnDate = $makeGetValue[1];
//print($GoodsReceiptNoteDetail);
//echo '<br />';
//print($GRNDataDetail);
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        &nbsp;
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo PurchaseHelper::purchasePaymentVoucherSummaryDetail($m,$grnNo);?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="hidden" name="grnNo" id="grnNo" value="<?php echo $grnNo ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="grnDate" id="grnDate" value="<?php echo $grnDate ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="subDepartmentId" id="subDepartmentId" value="<?php echo $GoodsReceiptNoteDetail->sub_department_id ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="supplierId" id="supplierId" value="<?php echo $GoodsReceiptNoteDetail->supplier_id; ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="supplierAccId" id="supplierAccId" value="<?php echo CommonHelper::getAccountIdByMasterTable($m,$GoodsReceiptNoteDetail->supplier_id,'supplier');?>" class="form-control" readonly/>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Slip No.</label>
                <input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no" id="slip_no" value="" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">PV Date.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date" id="pv_date" value="<?php echo date('Y-m-d') ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Cheque No.</label>
                <input type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no" id="cheque_no" value="" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Cheque Date.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date" id="cheque_date" value="<?php echo date('Y-m-d') ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Supplier</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <?php CommonHelper::companyDatabaseConnection($m);?>
                <input type="text" name="supplier_name" id="supplier_name" class="form-control" readonly value="<?php echo CommonHelper::getMasterTableValueById($m,'supplier','name',$GoodsReceiptNoteDetail->supplier_id);?>" >
                <?php CommonHelper::reconnectMasterDatabase();?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Debit Amount</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="debit_amount" id="debit_amount" class="form-control" readonly value="<?php echo CommonHelper::getTotalGRNAmountByGRNNo($m,$grnNo,'grn_data','subTotal','grn_no','grn_status');?>" >
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="sf-label">Description</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <textarea name="main_description" id="main_description" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered sf-table-list">
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Accout Head <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center col-sm-3">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 1;
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>
                                    <input type="hidden" name="seletedGoodsReceiptNoteRow[]" readonly id="seletedGoodsReceiptNoteRow" value="<?php echo $counter;?>" class="form-control" />
                                </td>
                                <td>
                                    <select class="form-control requiredField" name="account_id_<?php echo $counter?>" id="account_id_<?php echo $counter?>">
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $key => $y)
                                            <option value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="credit_<?php echo $counter?>" id="credit_<?php echo $counter?>" class="form-control requiredField" value="" placeholder="Please Put Credit Amount" onkeyup="checkPaidAmount(this.id)" />
                                </td>
                                <td class="text-center">---</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function updateDebitAmountinField() {
        var debitAmount = $('#debit_amount').val();
        var totalPaymentAmount = $('#totalPaymentAmount').val();

        var remainingBalance = parseInt(debitAmount) - parseInt(totalPaymentAmount);
        $('#debit_amount').val(remainingBalance)
    }
    updateDebitAmountinField();

    function checkPaidAmount(id) {
        //alert(id);
        var debitAmount = $('#debit_amount').val();
        var creditAmount = $('#'+id+'').val();
        if(parseInt(creditAmount) <= parseInt(debitAmount)){

        }else{
            $('#'+id+'').val('');
            $('#'+id+'').focus();
            alert('Something Wrong');
        }
    }
</script>
