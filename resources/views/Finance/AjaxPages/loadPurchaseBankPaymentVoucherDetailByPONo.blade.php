<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$m = $_GET['m'];
$makeGetValue = explode('*',$_GET['poDetail']);
$poNo = $makeGetValue[0];
$poDate = $makeGetValue[1];
$poId = $makeGetValue[2];
//print_r($purchaseOrderDetail);
//die;

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" name="poNo" id="poNo" value="<?php echo $poNo ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="poDate" id="poDate" value="<?php echo $poDate ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="poId" id="poId" value="<?php echo $poId ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="supplierId" id="supplierId" value="<?php echo $purchaseOrderDetail->supplier_id; ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="supplierAccId" id="supplierAccId" value="<?php echo CommonHelper::getAccountIdByMasterTable($m,$purchaseOrderDetail->supplier_id,'supplier');?>" class="form-control" readonly/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-sm-12">
            &nbsp;
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-sm-12">
        <?php 
                $ppsaPOResult = PurchaseHelper::priviousPaymentSummaryAgainstPO($poId);
                
            ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">S.No.</th>
                        <th class="text-center">P.V.No.</th>
                        <th class="text-center">P.V. Date</th>
                        <th class="text-center">Payment Amount</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $pssaPOCounter = 1;
                        $overAllPaymentAmount = 0;
                        foreach($ppsaPOResult as $ppsaPORow){
                            $overAllPaymentAmount += $ppsaPORow->amount;
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $pssaPOCounter++?></td>
                            <td class="text-center"><?php echo $ppsaPORow->pv_no?></td>
                            <td class="text-center"><?php echo $ppsaPORow->pv_date?></td>
                            <td class="text-right"><?php echo $ppsaPORow->amount?></td>
                            <td class="text-center"><?php if($ppsaPORow->pv_status == 1){echo 'Pending';}else{echo 'Approved';}?></td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
	<div class="row">
		<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Slip No.</label>
					<input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no" id="slip_no" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">PV Date.</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="date" class="form-control requiredField" name="pv_date" id="pv_date" value="<?php echo date('Y-m-d') ?>" />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Debit Amount</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="text" name="debit_amount" id="debit_amount" class="form-control" readonly value="<?php echo $purchaseOrderDetail->totalAmount+$purchaseOrderDetail->sales_tax_amount - $overAllPaymentAmount;?>" >
				</div>
			</div>
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="sf-label">Payment Type.</label>
                    <select name="paymentType" id="paymentType" class="form-control" onchange="changePaymentVoucherType()">
                        <option value="1">Bank</option>
                        <option value="2">Cash</option>
                    </select>
                </div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Cheque No.</label>
					<input type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no" id="cheque_no" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Cheque Date.</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="date" class="form-control requiredField" name="cheque_date" id="cheque_date" value="<?php echo date('Y-m-d') ?>" />
				</div>
			</div>
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="sf-label">Description</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<textarea name="main_description" id="main_description" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
				</div>
			</div>
		</div>
	</div>
	

    <div id="normalPaymentAmountSection">
        <div class="lineHeight">&nbsp;</div>
        <div class="well">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <th class="text-center">Accout Head <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center col-sm-3">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody class="addMorePurchaseBankPvsDetailRows">
                                    <?php
                                    $counter = 1;
                                    ?>
                                        <tr>
                                            <td>
                                                <?php $counter++;?>
                                                <input type="hidden" name="seletedPurchaseOrderRow[]" readonly id="seletedPurchaseOrderRow" value="<?php echo $counter;?>" class="form-control" />
                                                <select class="form-control requiredField" name="account_id_<?php echo $counter?>" id="account_id_<?php echo $counter?>">
                                                    <option value="">Select Account</option>
                                                    @foreach($accounts as $key => $y)
                                                        <option value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="credit_<?php echo $counter?>" id="credit_<?php echo $counter?>" class="form-control ccAmount requiredField" value="" placeholder="Please Put Credit Amount" onkeyup="checkPaidAmount(this.id)" />
                                            </td>
                                            <td class="text-center">---</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="button" class="btn btn-sm btn-primary" onclick="addMorePurchaseBankPvsDetailRows()" value="Add More Purchase Bank PV's Rows" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(function () {
        $("select").select2();
    });
    var x = '<?php echo $counter?>';
    function addMorePurchaseBankPvsDetailRows(){
        x++;
        var m = '<?php echo $m;?>';
        $.ajax({
            url: '<?php echo url('/')?>/fmfal/addMorePurchaseBankPvsDetailRows',
            type: "GET",
            data: { counter:x,m:m},
            success:function(data) {
                $('.addMorePurchaseBankPvsDetailRows').append(data);
                $("select").select2();
            }
        });
    }
    function removePurchaseBankPvsRows(counter){
        var elem = document.getElementById('removePurchaseBankPvsRows_'+counter+'');
        elem.parentNode.removeChild(elem);
    }
    function updateDebitAmountinField() {
        var debitAmount = $('#debit_amount').val();
        var totalPaymentAmount = $('#totalPaymentAmount').val();

        var remainingBalance = parseInt(debitAmount) - parseInt(totalPaymentAmount);
        //$('#debit_amount').val(remainingBalance)
    }
    updateDebitAmountinField();

    function checkPaidAmount(id) {
        //alert(id);
        var debitAmount = $('#debit_amount').val();
        var sumCreditAmount = 0;
        $('.ccAmount').each(function() {
            sumCreditAmount += Number($(this).val());
        });
        //var creditAmount = $('#'+id+'').val();
        if(parseInt(sumCreditAmount) <= parseInt(debitAmount)){
        }else{
            $('#'+id+'').val('');
            $('#'+id+'').focus();
            alert('Something Wrong');
        }
    }

    function changePaymentVoucherType(){
        var paymentType = $('#paymentType').val();
        if(paymentType == 1){
            $("#cheque_no").removeAttr('disabled');
            $("#cheque_date").removeAttr('disabled');

            $('#cheque_no').addClass('requiredField');
            $('#cheque_no').addClass('requiredField');

        }else if(paymentType == 2){
            $("#cheque_no").attr('disabled','disabled');
            $("#cheque_date").attr('disabled','disabled');

            $('#cheque_no').removeClass('requiredField');
            $('#cheque_date').removeClass('requiredField');
        }

    }
</script>
