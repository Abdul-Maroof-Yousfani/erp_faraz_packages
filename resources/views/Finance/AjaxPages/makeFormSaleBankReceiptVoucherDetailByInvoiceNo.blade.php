<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
$m = $_GET['m'];
$makeGetValue = explode('*',$_GET['invNo']);
$invNo = $makeGetValue[0];
$invDate = $makeGetValue[1];
//print($InvoiceDetail);
//echo '<br />';
//print($InvoiceDataDetail);
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        &nbsp;
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo SaleHelper::saleReceiptVoucherSummaryDetail($m,$invNo);?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="hidden" name="invoiceNo" id="invoiceNo" value="<?php echo $invNo ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="invoiceDate" id="invoiceDate" value="<?php echo $invDate ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="customerId" id="customerId" value="<?php echo $InvoiceDetail->consignee; ?>" class="form-control col-sm-6" readonly/>
        <input type="hidden" name="customerAccId" id="customerAccId" value="<?php echo CommonHelper::getAccountIdByMasterTable($m,$InvoiceDetail->consignee,'customers');?>" class="form-control" readonly/>
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
                <label class="sf-label">RV Date.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="rv_date" id="rv_date" value="<?php echo date('Y-m-d') ?>" />
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
                <input type="text" name="customer_name" id="customer_name" class="form-control" readonly value="<?php echo CommonHelper::getMasterTableValueById($m,'customers','name',$InvoiceDetail->consignee);?>" >
                <?php CommonHelper::reconnectMasterDatabase();?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Credit Amount</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="credit_amount" id="credit_amount" class="form-control" readonly value="<?php echo CommonHelper::getTotalInvoiceAmountByInvoiceNo($m,$invNo,'inv_data','amount','inv_no','inv_status');?>" >
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
                            <th class="text-center col-sm-3">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 1;
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $counter++;?>
                                    <input type="hidden" name="seletedInvoiceRow[]" readonly id="seletedInvoiceRow" value="<?php echo $counter;?>" class="form-control" />
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
                                    <input type="number" name="debit_<?php echo $counter?>" id="debit_<?php echo $counter?>" class="form-control requiredField" value="" placeholder="Please Put Debit Amount" onkeyup="checkPaidAmount(this.id)" />
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
        var creditAmount = $('#credit_amount').val();
        var totalReceiptAmount = $('#totalReceiptAmount').val();

        var remainingBalance = parseInt(creditAmount) - parseInt(totalReceiptAmount);
        $('#credit_amount').val(remainingBalance)
    }
    updateDebitAmountinField();

    function checkPaidAmount(id) {
        //alert(id);
        var creditAmount = $('#credit_amount').val();
        var debitAmount = $('#'+id+'').val();
        if(parseInt(debitAmount) <= parseInt(creditAmount)){

        }else{
            $('#'+id+'').val('');
            $('#'+id+'').focus();
            alert('Something Wrong');
        }
    }
</script>
