<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$m = $_GET['m'];
$makeGetValue = explode('*',$_GET['sqDetail']);
$soNo = $makeGetValue[0];
$soDate = $makeGetValue[1];
$soId = $makeGetValue[2];
//print_r($purchaseOrderDetail);
//die;


 $customer = CommonHelper::byers_name($saleQuotationDetail->buyers_id);

 $customerName = $customer->name ?? '';
?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" name="soNo" id="soNo" value="<?php echo $soNo ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="soDate" id="soDate" value="<?php echo $soDate ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="soId" id="soId" value="<?php echo $soId ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="customerId" id="customerId" value="<?php echo $saleQuotationDetail->buyers_id; ?>" class="form-control col-sm-6" readonly/>
            <input type="hidden" name="customerAccId" id="customerAccId" value="<?php echo SalesHelper::get_customer_acc_id($saleQuotationDetail->buyers_id);?>" class="form-control" readonly/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
            <a class="btn btn-sm btn-success" 
                onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{$saleQuotationDetail->id}}',{{$saleQuotationDetail->id}},'View Sale Order ')">
                            <i class="fa fa-eye" aria-hidden="true"></i> View So Detail
            </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <?php 
                $pssaSOResult = SalesHelper::priviousReceiptSummaryAgainstSO($saleQuotationDetail->id);
                
            ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">S.No.</th>
                        <th class="text-center">R.V.No.</th>
                        <th class="text-center">R.V. Date</th>
                        <th class="text-center">Receipt Amount</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $pssaSOCounter = 1;
                        $overAllReceiptAmount = 0;
                        foreach($pssaSOResult as $pssaSORow){
                            $overAllReceiptAmount += $pssaSORow->amount;
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $pssaSOCounter++?></td>
                            <td class="text-center"><?php echo $pssaSORow->rv_no?></td>
                            <td class="text-center"><?php echo $pssaSORow->rv_date?></td>
                            <td class="text-right"><?php echo $pssaSORow->amount?></td>
                            <td class="text-center"><?php if($pssaSORow->rv_status == 1){echo 'Pending';}else{echo 'Approved';}?></td>
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
            <div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">So No.</label>
					<input type="text" class="form-control requiredField" value="{{ $saleQuotationDetail->so_no }}" readonly/>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">So Date.</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="date" class="form-control requiredField"  value="{{ $saleQuotationDetail->so_date }}" readonly />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Customer</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="text" class="form-control" value="{{ $customerName }}" readonly />
				</div>
			</div>
        </div>
		<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Slip No.</label>
					<input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no" id="slip_no" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">RV Date.</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="date" class="form-control requiredField" name="rv_date" id="rv_date" value="<?php echo date('Y-m-d') ?>" />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<label class="sf-label">Credit Amount</label>
					<span class="rflabelsteric"><strong>*</strong></span>
					<input type="text" name="credit_amount" id="credit_amount" class="form-control" readonly value="<?php echo $saleQuotationDetail->total_amount_after_sale_tax - $overAllReceiptAmount;?>" >
				</div>
			</div>
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="sf-label">Receipt Type.</label>
                    <select name="receiptType" id="receiptType" class="form-control" onchange="changeReceiptVoucherType()">
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
                                        <th class="text-center col-sm-3">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody class="addMoreSalesRvsDetailRows">
                                    <?php
                                    $counter = 0;
                                    ?>
                                        <tr>
                                            <td>
                                                <?php $counter++;?>
                                                <input type="hidden" name="seletedSaleOrderRow[]" readonly id="seletedSaleOrderRow" value="<?php echo $counter;?>" class="form-control" />
                                                <select class="form-control requiredField" name="account_id_<?php echo $counter?>" id="account_id_<?php echo $counter?>">
                                                    <option value="">Select Account</option>
                                                    @foreach($accounts as $key => $y)
                                                        <option value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="debit_<?php echo $counter?>" id="debit_<?php echo $counter?>" class="form-control ddAmount requiredField" value="" placeholder="Please Put Debit Amount" onkeyup="checkReceiptAmount(this.id)" />
                                            </td>
                                            <td class="text-center">---</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php $counter++;?>
                                                <input type="hidden" name="seletedSaleOrderRow[]" readonly id="seletedSaleOrderRow" value="<?php echo $counter;?>" class="form-control" />
                                                <select class="form-control requiredField" name="account_id_<?php echo $counter?>" id="account_id_<?php echo $counter?>">
                                                    <option value="">Select Account</option>
                                                    @foreach(CommonHelper::get_gst_account() as $key => $y)
                                                        <option value="{{ $y->id}}" data-value="{{$y->rate}}" >
                                                            {{$y->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="debit_<?php echo $counter?>" id="debit_<?php echo $counter?>" class="form-control ddAmount requiredField" value="" placeholder="Please Put Debit Amount" onkeyup="checkReceiptAmount(this.id)" />
                                            </td>
                                            <td class="text-center">---</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="button" class="btn btn-sm btn-primary" onclick="addMoreSalesRvsDetailRows()" value="Add More Sale Order RV's Rows" />
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
    function addMoreSalesRvsDetailRows(){
        x++;
        var m = '<?php echo $m;?>';
        $.ajax({
            url: '<?php echo url('/')?>/smfal/addMoreSalesRvsDetailRows',
            type: "GET",
            data: { 
                        counter:x,
                        m:m,
                        code: "1-2-8,1-2-9"
                    },
            success:function(data) {
                $('.addMoreSalesRvsDetailRows').append(data);
                $("select").select2();
            }
        });
    }
    function removeSaleRvsRows(counter){
        var elem = document.getElementById('removeSaleRvsRows_'+counter+'');
        elem.parentNode.removeChild(elem);
    }
    function updateDebitAmountinField() {
        var debitAmount = $('#debit_amount').val();
        var totalPaymentAmount = $('#totalPaymentAmount').val();

        var remainingBalance = parseInt(debitAmount) - parseInt(totalPaymentAmount);
        //$('#debit_amount').val(remainingBalance)
    }
    updateDebitAmountinField();

    function checkReceiptAmount(id) {
        //alert(id);
        var creditAmount = $('#credit_amount').val();
        var sumDebitAmount = 0;
        $('.ddAmount').each(function() {
            sumDebitAmount += Number($(this).val());
        });
        //var creditAmount = $('#'+id+'').val();
        if(parseInt(sumDebitAmount) <= parseInt(creditAmount)){
        }else{
            $('#'+id+'').val('');
            $('#'+id+'').focus();
            alert('Something Wrong');
        }
    }

    function changeReceiptVoucherType(){
        var receiptType = $('#receiptType').val();
        if(receiptType == 1){
            $("#cheque_no").removeAttr('disabled');
            $("#cheque_date").removeAttr('disabled');
            
            $('#cheque_no').addClass('requiredField');
            $('#cheque_no').addClass('requiredField');


        }else if(receiptType == 2){
            $("#cheque_no").attr('disabled','disabled');
            $("#cheque_date").attr('disabled','disabled');

            $('#cheque_no').removeClass('requiredField');
            $('#cheque_date').removeClass('requiredField');
        }

    }
</script>
