<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$m = $_GET['m'];
$makeGetValue = explode('*',$_GET['GrnNo']);
$GrnId = $makeGetValue[0];
$GrnNo = $makeGetValue[1];
$GrnDate = $makeGetValue[2];
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
                    <th class="text-center">Batch Code</th>
                    <th class="text-center">Received Qty</th>
                    <th class="text-center"> Return QTY</th>
                    <th  style="display: none" class="text-center">Rate</th>
                    <th style="display: none" class="text-center">Amount</th>
                    <th class="text-center">Stock Qty</th>
                    <th class="text-center">Return Qty</th>
                    <th class="text-center">Enable/Disable</th>
                </thead>
                <tbody>
                <?php
                $Counter = 1;
                        $Count = 0;
                foreach($DataDetail as $Fil):
                ?>
                <input type="hidden" name="grn_data_id[]" value="{{$Fil->id}}"/>
                    <tr class="text-center">
                        <td><?php echo $Counter++;?></td>

                        <input type="hidden" name="GrnDataId[]" readonly  value="<?php echo $Fil->id;?>" class="form-control" />
                        <td>
                            <?php //echo $Fil->description//CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$Fil->sub_item_id);?>
                            <input type="hidden" name="SubItemId[]" readonly id="subItemId_<?php echo $Fil->id;?>" value="<?php echo $Fil->sub_item_id;?>" class="form-control" />
                            <textarea  name="item_desc[]" readonly id="item_desc<?php echo $Fil->id;?>" class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"><?php echo CommonHelper::get_item_name($Fil->sub_item_id);?></textarea>
                        </td>
                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$Fil->warehouse_id);?>
                            <input value="<?php echo $Fil->warehouse_id?>" type="hidden" name="WarehouseId[]" id="warehouse_id_<?php echo $Fil->id; ?>"/>
                        </td>
                        <td><?php echo $Fil->batch_code?>
                            <input type="hidden" name="BatchCode[]" id="BatchCode<?php echo $Fil->id?>" value="<?php echo $Fil->batch_code;?>">
                        </td>
                        <td class="text-center"><?php echo number_format($Fil->purchase_recived_qty,2);?>
                            <input value="<?php echo $Fil->purchase_recived_qty?>" type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_<?php echo $Fil->id; ?>"/>
                        </td>
                        <?php $reurn=0; ?>
                        <?php $return_qty=DB::Connection('mysql2')->selectOne('select sum(return_qty)qty from purchase_return_data where status=1 and grn_data_id="'.$Fil->id.'" group by grn_data_id') ?>
                        <td class="text-center">@if(!empty($return_qty->qty)){{$reurn=$return_qty->qty}} @else <?php  ?> @endif</td>
                            <input type="hidden" id="return_<?php echo $Fil->id; ?>" value="{{$reurn}}"/>

                        <td style="display: none" class="text-center"><?php echo number_format($Fil->rate,2);?>
                            <input value="<?php echo $Fil->rate?>" type="hidden" name="Rate[]" id="rate_<?php echo $Fil->id; ?>"/>
                        </td>
                        <td style="display: none" class="text-center"><?php echo number_format($Fil->amount,2);?>
                            <input value="<?php echo $Fil->amount?>" type="hidden" name="Amount[]" id="amount_<?php echo $Fil->id; ?>"/>
                        </td>

                        <?php $type =  CommonHelper::get_item_type($Fil->sub_item_id); ?>

                      
                        <td >
                            <input type="number" class="form-control" id="stock_qty<?php echo $Fil->id?>" name="stock_qty[]" value="{{ReuseableCode::get_stock($Fil->sub_item_id,$Fil->warehouse_id,0,$Fil->batch_code)}}" readonly>
                        </td>
                        <td>
                            <input type="number" step="any" class="form-control" id="return_qty_<?php echo $Fil->id?>" name="ReturnQty[]" value="0.00" readonly onkeyup="check_val('<?php echo $Fil->id?>')">
                        </td>


                        <td>
                            <input type="checkbox" name="enable_disable[]" id="enable_disable_<?php echo $Fil->id?>" value="<?php echo $Count;?>" class="form-control amount" style="height: 25px !important;" onclick="ChkUnChk('<?php echo $Fil->id?>')">
                        </td>

                        <input type="hidden" name="discount_percent[]" value="{{$Fil->discount_percent}}"/>
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
        <label for="">Good Receipt Not Date</label>
        <input type="date" id="GrnDate" name="GrnDate" value="<?php echo $GrnDate?>" class="form-control" readonly>
        <input type="hidden" id="GrnNo" name="GrnNo" value="<?php echo $GrnNo?>" class="form-control" readonly>
        <input type="hidden" id="GrnId" name="GrnId" value="<?php echo $GrnId?>" class="form-control" readonly>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="">Remarks</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <textarea name="Remarks" id="Remarks" cols="30" rows="3" class="form-control requiredField" ></textarea>
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
