<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$m = $_GET['m'];
$makeGetValue = explode('*', $_GET['PurchaseInvoiceNo']);
$InvoiceId = $makeGetValue[0] ?? 0;
$InvoiceNo = $makeGetValue[1] ?? '';
$InvoiceDate = $makeGetValue[2] ?? '';
?>
@include('number_formate')
@include('select2')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list">
                <thead>
                    <th class="text-center">Sr.No</th>
                    <th class="text-center">Item Name</th>
                    <th class="text-center">Location</th>
                    <th class="text-center hide">Batch Code</th>
                    <th class="text-center">Purchase Qty</th>
                    <th class="text-center">Return Qty Sum Total</th>
                    <th style="display: none" class="text-center">Rate</th>
                    <th style="display: none" class="text-center">Amount</th>
                    <th class="text-center hide">Purchase Remaining Qty</th>
                    <th class="text-center">Return Qty</th>
                    <th class="text-center">Enable/Disable</th>
                </thead>
                <tbody>
                <?php
                $Counter = 1;
                $Count = 0;
                foreach($DataDetail as $Fil):
                    $lineKey = $Count;
                    $grnDataId = $Fil->purchase_grn_data_id ?? 0;
                    $itemId = $Fil->sub_item ?? 0;
                    $warehouseId = $Fil->warehouse_id ?? 0;
                    $batchCode = $Fil->batch_code ?? '';
                    $purchaseQty = $Fil->purchase_recived_qty ?? 0;
                    $rate = $Fil->rate ?? 0;
                    $amount = $Fil->amount ?? 0;
                    $discountPercent = $Fil->discount_percent ?? 0;
                    $reurn = 0;
                    if (!empty($InvoiceId) && !empty($itemId)) {
                        $reurn = (float) DB::connection('mysql2')->table('purchase_return_data')
                            ->where('status', 1)
                            ->where('purchase_invoice_id', $InvoiceId)
                            ->where('sub_item_id', $itemId)
                            ->sum('return_qty');
                    }
                    $remainingQty = max(((float) $purchaseQty) - $reurn, 0);
                ?>
                <input type="hidden" name="grn_data_id[]" value="{{ $grnDataId }}"/>
                    <tr class="text-center">
                        <td><?php echo $Counter++;?></td>

                        <input type="hidden" name="GrnDataId[]" readonly value="<?php echo $grnDataId; ?>" class="form-control" />
                        <td>
                            <input type="hidden" name="SubItemId[]" readonly id="subItemId_<?php echo $lineKey; ?>" value="<?php echo $itemId; ?>" class="form-control" />
                            <textarea name="item_desc[]" readonly id="item_desc<?php echo $lineKey; ?>" class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"><?php echo CommonHelper::get_item_name($itemId);?></textarea>
                        </td>
                        <td>
                            <?php echo $warehouseId ? CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$warehouseId) : ''; ?>
                            <input value="<?php echo $warehouseId?>" type="hidden" name="WarehouseId[]" id="warehouse_id_<?php echo $lineKey; ?>"/>
                        </td>
                        <td class="hide">
                            <?php echo $batchCode; ?>
                            <input type="hidden" name="BatchCode[]" id="BatchCode<?php echo $lineKey?>" value="<?php echo $batchCode; ?>">
                        </td>
                        <td class="text-center"><?php echo number_format($purchaseQty,2);?>
                            <input value="<?php echo $purchaseQty?>" type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_<?php echo $lineKey; ?>"/>
                        </td>
                        <td class="text-center"><?php echo number_format($reurn,2); ?></td>
                        <input type="hidden" id="return_<?php echo $lineKey; ?>" value="<?php echo $reurn; ?>"/>

                        <td style="display: none" class="text-center"><?php echo number_format($rate,2);?>
                            <input value="<?php echo $rate?>" type="hidden" name="Rate[]" id="rate_<?php echo $lineKey; ?>"/>
                        </td>
                        <td style="display: none" class="text-center"><?php echo number_format($amount,2);?>
                            <input value="<?php echo $amount?>" type="hidden" name="Amount[]" id="amount_<?php echo $lineKey; ?>"/>
                        </td>

                        <td>
                            <input type="number" class="form-control" id="stock_qty<?php echo $lineKey?>" name="stock_qty[]" value="{{ number_format($remainingQty,2,'.','') }}" readonly>
                        </td>
                        <td>
                            <input type="number" step="any" class="form-control" id="return_qty_<?php echo $lineKey?>" name="ReturnQty[]" value="0.00" readonly onkeyup="check_val('<?php echo $lineKey?>')">
                        </td>
                        <td>
                            <input type="checkbox" name="enable_disable[]" id="enable_disable_<?php echo $lineKey?>" value="<?php echo $lineKey;?>" class="form-control amount" style="height: 25px !important;" onclick="ChkUnChk('<?php echo $lineKey?>')">
                        </td>

                        <input type="hidden" name="discount_percent[]" value="<?php echo $discountPercent; ?>"/>
                    </tr>
                <?php
                    $Count++;
                endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?php
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`pr_no`,3,length(substr(`pr_no`,3))-4),signed integer)) reg from `purchase_return` where substr(`pr_no`,-4,2) = " . date('m') . " and substr(`pr_no`,-2,2) = " . date('y') . "")->reg;
        $PurchaseReturnNo = 'dr' . ($str + 1) . date('my');
        ?>
        <label for="">Purchase Return No</label>
        <input type="text" class="form-control" id="" value="<?php echo strtoupper($PurchaseReturnNo)?>" readonly>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label for="">Purchase Return Date</label>
        <input type="date" id="PurchaseReturnDate" name="PurchaseReturnDate" value="<?php echo date('Y-m-d')?>" class="form-control">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label for="">Purchase Invoice Date</label>
        <input type="date" id="InvoiceDate" name="InvoiceDate" value="<?php echo $InvoiceDate?>" class="form-control" readonly>
        <input type="hidden" id="PurchaseInvoiceNo" name="PurchaseInvoiceNo" value="<?php echo $InvoiceNo?>" class="form-control" readonly>
        <input type="hidden" id="PurchaseInvoiceId" name="PurchaseInvoiceId" value="<?php echo $InvoiceId?>" class="form-control" readonly>
        <input type="hidden" id="PurchaseInvoiceDate" name="PurchaseInvoiceDate" value="<?php echo $InvoiceDate?>" class="form-control" readonly>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="">Remarks</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <textarea name="Remarks" id="Remarks" cols="30" rows="3" class="form-control requiredField"></textarea>
    </div>
</div>
<script !src="">

    $('.ReturnQty').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    function check_val(Id)
    {
        var stock_qty=parseFloat($('#stock_qty'+Id).val());
        var ReturnQty = parseFloat($('#return_qty_'+Id).val());

        var ActualQty = parseFloat($('#purchase_recived_qty_'+Id).val());
        var returnn = parseFloat($('#return_'+Id).val());

        ActualQty=ActualQty - returnn;

        if(ReturnQty > ActualQty)
        {
            alert('Please Correct Your Return Qty....!');
            $('#return_qty_'+Id).val('');
        }
        else
        {
           if (ReturnQty >stock_qty)
           {
               alert('Please Correct Your Return Qty....!');
               $('#return_qty_'+Id).val('');
           }
        }
    }

    function ChkUnChk(Id) {
        if ($('#enable_disable_' + Id).prop("checked") == true) {
            $('#return_qty_' + Id).prop('readonly', false).addClass('requiredField');
            $('#return_qty_' + Id).val('');
        } else {
            $('#return_qty_' + Id).prop('readonly', true).removeClass('requiredField');
            $('#return_qty_' + Id).val('0.00');
        }
    }

    function validateForm(event) {
        if ($('input[type="checkbox"]:checked').length === 0) {
            alert('Please select at least one checkbox before submitting the form.');
            event.preventDefault();
            return false;
        }
        return true;
    }

    $(document).ready(function () {
        $('#addPurchaseReturnDetail').on('submit', validateForm);
    });

</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
