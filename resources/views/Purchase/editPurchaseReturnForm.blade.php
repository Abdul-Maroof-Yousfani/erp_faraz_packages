<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$m = $_GET['m'];

?>
@extends('layouts.default')

@section('content')
@include('number_formate')
@include('select2')
<div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit Purchase Return Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <?php echo Form::open(array('url' => 'pad/updatePurchaseReturnDetail?m='.$m.'','id'=>'addPurchaseReturnDetail'));?>
                            <div class="row">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                        <th class="text-center">Sr.No</th>
                                                        <th class="text-center">Item Name</th>
                                                        <th class="text-center">Location</th>
                                                        <th class="text-center">Batch Code</th>
                                                        <th class="text-center"> Received Qty</th>
                                                        <th class="text-center"> Return QTY</th>
                                                        <th class="text-center">Rate</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Stock Qty</th>
                                                        <th class="text-center">Return Qty</th>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $Counter = 1;
                                                        $Count = 0;
                                                        foreach($Detail as $Fil):
                                                        ?>
                                                        <input type="hidden" name="grn_data_id[]" value="{{$Fil->grn_data_id}}"/>
                                                        <tr class="text-center">
                                                            <td><?php echo $Counter++;?></td>

                                                            <input type="hidden" name="GrnDataId[]" readonly  value="<?php echo $Fil->grn_data_id;?>" class="form-control" />
                                                            <td>
                                                                <?php //echo  $Fil->description;//echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$Fil->sub_item_id);?>
                                                                <input type="hidden" name="SubItemId[]" readonly id="subItemId_<?php echo $Fil->grn_data_id;?>" value="<?php echo $Fil->sub_item_id;?>" class="form-control" />
                                                                <textarea  name="item_desc[]" readonly id="item_desc<?php echo $Fil->grn_data_id;?>"  class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"><?php echo $Fil->description;?></textarea>
                                                            </td>
                                                            <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$Fil->warehouse_id);?>
                                                                <input value="<?php echo $Fil->warehouse_id?>" type="hidden" name="WarehouseId[]" id="warehouse_id_<?php echo $Fil->grn_data_id; ?>"/>
                                                            </td>
                                                            <td><?php echo $Fil->batch_code;?>
                                                                <input type="hidden" name="BatchCode[]" id="BatchCode<?php echo $Fil->grn_data_id?>" value="<?php echo $Fil->batch_code?>">
                                                            </td>
                                                            <td class="text-center"><?php echo number_format($Fil->recived_qty,2);?>
                                                                <input value="<?php echo $Fil->recived_qty?>" type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_<?php echo $Fil->grn_data_id; ?>"/>
                                                            </td>
                                                            <?php $reurn=0; ?>
                                                            <?php $return_qty=DB::Connection('mysql2')->selectOne('select sum(return_qty)qty from purchase_return_data where status=1 and
                                                            grn_data_id="'.$Fil->grn_data_id.'" and id!="'.$Fil->id.'" group by grn_data_id') ?>
                                                            <td class="text-center">@if(!empty($return_qty->qty)){{$reurn=$return_qty->qty}} @else <?php  ?> @endif</td>
                                                            <input type="hidden" id="return_<?php echo $Fil->id; ?>" value="{{$reurn}}"/>

                                                            <td class="text-center"><?php echo number_format($Fil->rate,2);?>
                                                                <input value="<?php echo $Fil->rate?>" type="hidden" name="Rate[]" id="rate_<?php echo $Fil->grn_data_id; ?>"/>
                                                            </td>
                                                            <td class="text-center"><?php echo number_format($Fil->amount,2);?>
                                                                <input value="<?php echo $Fil->amount?>" type="hidden" name="Amount[]" id="amount_<?php echo $Fil->grn_data_id; ?>"/>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control" id="stock_qty<?php echo $Fil->grn_data_id?>" name="stock_qty[]" value="{{ReuseableCode::get_stock($Fil->sub_item_id,$Fil->warehouse_id,0,$Fil->batch_code)+$Fil->return_qty}}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control ReturnQty" id="return_qty_<?php echo $Fil->grn_data_id?>" name="ReturnQty[]" value="<?php echo $Fil->return_qty?>" onkeyup="check_val('<?php echo $Fil->grn_data_id?>')">
                                                                <input type="hidden" name="LoopVal[]" value="<?php echo $Count;?>">
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

                                                $PurchaseReturnNo = $Master->pr_no;
                                                ?>
                                                <label for="">Purchase Return No</label>
                                                <input name="PrNo" type="text" class="form-control" id="" value="<?php echo strtoupper($PurchaseReturnNo)?>" readonly>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Purchase Return Date</label>
                                                <input type="date" id="PurchaseReturnDate" name="PurchaseReturnDate" value="<?php echo $Master->pr_date?>" class="form-control">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Good Receipt Not Date</label>
                                                <input type="hidden" name="EditId" value="<?php echo $Master->id?>">
                                                <input type="hidden" name="supplier" value="<?php echo $Master->supplier_id?>">
                                                <input type="date" id="GrnDate" name="GrnDate" value="<?php echo $Master->grn_date?>" class="form-control" readonly>
                                                <input type="hidden" id="GrnNo" name="GrnNo" value="<?php echo $Master->grn_no?>" class="form-control" readonly>
                                                <input type="hidden" id="GrnId" name="GrnId" value="<?php echo $Master->grn_id?>" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="">Remarks</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="Remarks" id="Remarks" cols="30" rows="3" class="form-control requiredField" placeholder="REMARKS"><?php echo $Master->remarks;?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) }}
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                </div>
                            </div>
                            <?php echo Form::close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    function ChkUnChk(Id)
    {

        if($('#enable_disable_'+Id).prop("checked") == true)
        {
            $('#return_qty_'+Id).prop('readonly',false);
            $('#return_qty_'+Id).val('');
        }
        else
        {
            $('#return_qty_'+Id).prop('readonly',true);
            $('#return_qty_'+Id).val('0.00');
        }
    }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection