<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$m = $_GET['m'];
//$makeGetValue = explode('*',$_GET['prNo']);
//$prNo = $makeGetValue[0];
//$prDate = $makeGetValue[1];

$str = DB::connection('mysql2')->selectOne("select max(convert(substr(`grn_no`,4,length(substr(`grn_no`,4))-4),signed integer)) reg from `goods_receipt_note` where substr(`grn_no`,-4,2) = " . date('m') . " and substr(`grn_no`,-2,2) = " . date('y') . "")->reg;
$grn_no = 'grn' . ($str + 1) . date('my');
?>
<style>
    .success {
        background-color: #ddffdd;
        border-left: 6px solid #4CAF50;
    }
</style>
@include('number_formate')
@include('select2')
<?php echo Form::open(array('url' => 'pad/addGoodsReceiptNoteDetail?m=' . $m . '', 'id' => 'addGoodsReceiptNoteDetail', 'enctype' => 'multipart/form-data'));?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">
    <div>
        <input type="hidden" name="prNo" id="prNo" value="<?php echo $prNo ?>" class="form-control" readonly />
        <input type="hidden" name="prDate" id="prDate" value="<?php echo $prDate ?>" class="form-control" readonly />
        <input type="hidden" name="subDepartmentId" id="subDepartmentId"
            value="<?php echo $purchaseRequestDetail->sub_department_id ?>" class="form-control" readonly />
        <input type="hidden" name="supplierId" id="supplierId"
            value="<?php echo $purchaseRequestDetail->supplier_id; ?>" class="form-control" readonly />
        <input type="hidden" name="demandType" id="demandType"
            value="<?php echo $purchaseRequestDetail->demand_type; ?>" class="form-control" readonly />
        <input type="hidden" value="{{ $purchaseRequestDetail->p_type }}" name="p_type_id" />

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">GRN NO.</label>
                <input readonly type="text" class="form-control requiredField" placeholder="" name="grn_no" id="grn_no"
                    value="{{strtoupper($grn_no)}}" />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">GRN Date.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="grn_date"
                    id="grn_date" value="<?php echo date('Y-m-d') ?>" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">PO NO.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input readonly type="text" class="form-control requiredField" placeholder="" name="po_no" id="po_no"
                    value="{{strtoupper($prNo)}}" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">PO Date.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="date" class="form-control requiredField" placeholder="" name="po_date" id="po_date"
                    value="{{$prDate}}" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Bill Date.</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input autofocus type="date" class="form-control requiredField" placeholder="" name="bill_date"
                    id="bill_date" value="<?php echo date('Y-m-d')?>" />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Supplier Invoice No</label></span>
                <input type="text" class="form-control " placeholder="Supplier Invoice No" name="invoice_no"
                    id="invoice_no" value="-" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Delivery Challan No</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input required type="text" class="form-control requiredField" placeholder="Delivery Challan No"
                    name="del_chal_no" id="del_chal_no" value="" />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Delivery Detail/ Vehicle # </label>
                <input type="text" class="form-control" placeholder="Delivery Detail/ Vehicle #" name="del_detail"
                    id="del_detail" value="" />
            </div>

            <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label"> Sub Department</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <input type="text" name="sub_department_name" id="sub_department_name" class="form-control" readonly value="<?php echo CommonHelper::getMasterTableValueById($m, 'sub_department', 'sub_department_name', $purchaseRequestDetail->sub_department_id);?>" >

            </div> -->
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                <label class="sf-label"> TRN</label>
                <input type="text" name="trn" id="trn" class="form-control" readonly
                    value="<?php echo $purchaseRequestDetail->trn?>">

            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Supplier</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <?php CommonHelper::companyDatabaseConnection($m);?>
                <input type="text" name="supplier_name" id="supplier_name" class="form-control" readonly
                    value="<?php echo CommonHelper::getMasterTableValueById($m, 'supplier', 'name', $purchaseRequestDetail->supplier_id);?>">
                <?php CommonHelper::reconnectMasterDatabase();?>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hide">
                <label class="sf-label">Supplier address</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <?php CommonHelper::companyDatabaseConnection($m);?>
                <input type="text" name="supplier_name" id="supplier_name" class="form-control" readonly
                    value="<?php echo CommonHelper::get_supplier_address($purchaseRequestDetail->supplier_id);?>">
                <?php CommonHelper::reconnectMasterDatabase();?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">Attachment</label>
                <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf, .doc, .docx, .jpg, .png">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="sf-label">Description</label>
                <textarea name="main_description" id="main_description" rows="4" cols="50" style="resize:none;"
                    class="form-control"></textarea>
            </div>
        </div>
        <div class="row">

        </div>
    </div>
    <input type="hidden" name="currency" id="currency" value="{{ $purchaseRequestDetail->currency_rate }}" />


</div>
<div class="lineHeight">&nbsp;</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="">Show Detail <input type="checkbox" id="CheckUnCheck"></label>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
            <table class="table table-bordered sf-table-list">
                <thead>
                    <tr>
                        <th style="width: 347px" class="text-center">Item Name</th>

                        <th class="text-center ShowHideDesc" style="width: 150px;">Batch Code</th>
                        <th class="text-center ShowHideDesc" style="width: 150px;">Expiry Date</th>
                        <th style="width: 100px" class="text-center">Ordered Qty</th>
                        <th style="width: 100px" class="text-center">Previous Received</th>
                        <th style="width: 110px"> Received Qty</th>
                        <th class="ShowHideRate" style="width: 110px;"> Rate</th>
                        <th class="ShowHideAmount" style="width: 110px;"> Amount</th>
                        <th class="ShowHideDiscountPercent" style="width: 110px;"> Discount %</th>
                        <th class="ShowHideDiscountAmount" style="width: 110px;"> Discount Amount</th>
                        <th class="ShowHideNetAmount" style="width: 110px;"> Net Amount</th>
                        <th style="width: 100px" class="text-center">BAL. QTY. Receivable</th>
                        <th style="width: 130px" class="text-center">Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    $net_amount = 0;
                    ?>

                    @foreach ($purchaseRequestDataDetail as $row)
                        <?php

                        $net_amount += $row->net_amount;

                        $grn_data = DB::Connection('mysql2')->table('grn_data')->select(DB::raw('SUM(purchase_recived_qty) as purchase_recived_qty'))->where('status', 1)->where('po_data_id', $row->id)->groupBy('po_data_id');
                        $grn_data_count = $grn_data->count();
                        if ($grn_data_count > 0) {
                            $grn_data = $grn_data->first();
                            $purchase_recived_qty = $grn_data->purchase_recived_qty;
                        } else {
                            $purchase_recived_qty = 0;
                        }
                        $qty_cond = $row->purchase_approve_qty - $purchase_recived_qty;

                        ?>
                        @if ($qty_cond != 0)
                            <input type="hidden" id="po_data_id<?php        echo $row->id;?>" name="po_data_id<?php        echo $row->id;?>"
                                value="{{$row->id}}" />

                            <?php  
                            $sub_ic_detail = CommonHelper::get_subitem_detail($row->sub_item_id);
                            $sub_ic_detail = explode(',', $sub_ic_detail)
                            ?>

                            <tr>

                                <input type="hidden" name="seletedPurchaseRequestRow[]" readonly id="seletedPurchaseRequestRow"
                                    value="<?php        echo $row->id;?>" class="form-control" />

                                <input type="hidden" name="subItemId_<?php        echo $row->id;?>" readonly
                                    id="subItemId_<?php        echo $row->id;?>" value="<?php        echo $row->sub_item_id;?>"
                                    class="form-control" />
                                <td>
                                    <?php  echo CommonHelper::getCompanyDatabaseTableValueById($m, 'subitem', 'sub_ic', $row->sub_item_id);?>
                                </td>
                                <?php
                                $batch_code = ReuseableCode::Batch_code_generate($row->sub_item_id);
                                $batch_code = 0;

                                ?>
                                <input readonly class="form-control" style="font-size: smaller" type="hidden"
                                    name="des{{$row->id}}" value="{{$row->description}}" />
                                <td class=""><input class="form-control requiredField" type="text" name="batch_code{{$row->id}}"
                                        value="{{ $batch_code }}" required /> </td>
                                <td> <input type="date" id="expiry_datees{{$row->id}}" class="form-control"
                                        name="expiry_date{{$row->id}}" /> </td>

                                <!--Quantity Orderd-->
                                <td class="text-center">{{number_format($row->purchase_approve_qty, 2)}}
                                    <input value="{{$qty_cond}}" type="hidden" name="approved_qty_<?php        echo $row->id; ?>"
                                        id="approved_qty_<?php        echo $row->id; ?>" />
                                </td>

                                <!--Reveived-->
                                <td class="text-center">{{$purchase_recived_qty}}</td>

                                <?php        $remaining_qty = $row->purchase_approve_qty - $purchase_recived_qty ?>
                                <!--Quantity Received-->
                                <td><input onkeyup="calculation('<?php echo $row->id; ?>');ShowAmount('<?php        echo $row->id?>')"
                                        onblur="calculation('<?php echo $row->id; ?>');ShowAmount('<?php        echo $row->id?>')"
                                        name="rec_qty_<?php  echo $row->id; ?>" id="rec_qty_<?php        echo $row->id; ?>" class="form-control
                            requiredFieldrequiredField rec_qty_<?php        echo $counter ?> loop" type="text"
                                        value="{{$remaining_qty}}" /> </td>
                                <!--Balance Quantity Receivable-->
                                <td class="ShowHideRate"><input style="width: 130px !important;" type="text"
                                        class="form-control" name="rate<?php        echo $row->id?>" id="rate<?php        echo $row->id?>"
                                        value="<?php        echo $row->rate?>" readonly></td>
                                <td class="ShowHideAmount"><input style="width: 130px !important;" type="text"
                                        class="form-control" name="amount<?php        echo $row->id?>" id="amount<?php        echo $row->id?>"
                                        value="<?php        echo $remaining_qty * $row->rate * $purchaseRequestDetail->currency_rate?>"
                                        readonly></td>

                                <?php
                                $amount = $remaining_qty * $row->rate;
                                $discount_percent = $row->discount_percent;
                                if ($discount_percent > 0):
                                    $discount_amount = ($amount / 100) * $discount_percent;
                                else:
                                    $discount_amount = 0;
                                endif; ?>
                                <td class="ShowHideDiscountPercent"><input style="width: 130px !important;" type="text"
                                        onkeyup="discount_percent(this.id)" class="form-control"
                                        name="discount_percent<?php        echo $row->id?>" id="discount_percent<?php        echo $row->id?>"
                                        value="<?php        echo number_format($row->discount_percent, 2)?>" readonly></td>
                                <td class="ShowHideDiscountAmount"><input style="width: 130px !important;" type="text"
                                        class="form-control" name="discount_amount<?php        echo $row->id?>"
                                        id="discount_amount<?php        echo $row->id?>" value="<?php        echo $discount_amount?>"
                                        readonly></td>
                                <td class="ShowHideNetAmount"><input style="width: 130px !important;" type="text"
                                        class="form-control net_amount_dis" name="after_discount_amount<?php        echo $row->id?>"
                                        id="after_dis_amountt_<?php        echo $row->id?>"
                                        value="<?php        echo $amount - $discount_amount?>" readonly></td>
                                <td><input style="width: 130px !important;" readonly name="balqty_<?php        echo $row->id; ?>"
                                        id="balqty_<?php        echo $row->id; ?>" class="form-control " type="text" value="0" /> </td>
                                <td>
                                    <select required style="width: 130px !important;" class="form-control requiredField ClsAll"
                                        name="warehouse_id_<?php        echo $row->id; ?>" id="warehouse_id_<?php        echo $row->id; ?>"
                                        onchange="ApplyAll('<?php        echo $row->id?>','<?php        echo $counter?>')">
                                        <option value="">Select</option>
                                        @foreach(CommonHelper::get_all_warehouse() as $row)
                                            <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            </tr>

                            <input style="width: 130px !important;" type="hidden" id="amount_<?php        echo $row->id ?>"
                                value="{{$row->net_amount}}" />
                            <input style="width: 130px !important;" type="hidden" name="exchange_rate" id="exchange_rate"
                                value="{{$currency_rate}}">

                            <?php $counter++; ?>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <span class="subHeadingLabelClass">Addional Expenses</span>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list">
                <thead>
                    <th class="text-center">Account Head</th>
                    <th class="text-center">Expense Amount</th>
                    <th class="text-center">
                        <button type="button" class="btn btn-xs btn-primary" id="BtnAddMoreExpense"
                            onclick="AddMoreExpense()">Add More</button>
                    </th>
                </thead>
                <tbody id="AppendExpense">

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{ Form::submit('Submit', ['class' => 'btn btn-success', 'id' => 'BtnSubmit']) }}
        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
    </div>
</div>
<?php echo Form::close();?>



<script>

    function ApplyAll(Row, Cnt) {
        if (Cnt == 1) {
            var selectedVal = $('#warehouse_id_' + Row).val();
            $('.ClsAll').val(selectedVal);
        }
    }

    $(document).ready(function () {
        $('.ShowHideRate').fadeOut();
        $('.ShowHideAmount').fadeOut();
        $('.ShowHideDiscountPercent').fadeOut();
        $('.ShowHideDiscountAmount').fadeOut();
        $('.ShowHideNetAmount').fadeOut();
        $('.select').select2();
        $('#account_id1').select2();
        var count = '<?php echo $counter ?>';
        for (i = 1; i < count; i++) {
            $('#qty_recived_' + i).number(true, 2);
            $('#balqty_' + i).number(true, 2);
            $('#balqty_' + i).number(true, 2);
            $('.carton_' + i).number(true, 2);
            $('.cost_in_pkr_' + i).number(true, 2);
            $('.total_landed_' + i).number(true, 2);
            $('.total_cash_flow_' + i).number(true, 2);

        }

        $('.loop').each(function () {
            var id = this.id;
            id = id.replace("rec_qty_", "");
            calculation(id);
        });

    });


    $('input[id="CheckUnCheck"]').click(function () {
        if ($(this).prop("checked") == true) {
            $('.ShowHideRate').fadeIn();
            $('.ShowHideDesc').fadeIn();
            $('.ShowHideAmount').fadeIn();
            $('.ShowHideDiscountPercent').fadeIn();
            $('.ShowHideDiscountAmount').fadeIn();
            $('.ShowHideNetAmount').fadeIn();

        }
        else if ($(this).prop("checked") == false) {
            $('.ShowHideRate').fadeOut();
            $('.ShowHideAmount').fadeOut();
            $('.ShowHideDiscountPercent').fadeOut();
            $('.ShowHideDiscountAmount').fadeOut();
            $('.ShowHideNetAmount').fadeOut();
            $('.ShowHideDesc').fadeIn();
        }
    });

    function ShowAmount(Id) {
        Amount = 0;
        var Qty = parseFloat($('#rec_qty_' + Id).val());
        var Rate = parseFloat($('#rate' + Id).val());
        Amount = (Qty * Rate).toFixed(2);
        if (isNaN(Amount)) {
            $('#amount' + Id).val(0);
        }
        else {
            $('#amount' + Id).val(Amount);
        }

    }


    function costing_calcu(id, number) {
        var b_c_opening_charges = parseFloat($('#b_c_opening_charges_' + number).val());
        b_c_opening_charges = Nancheck(b_c_opening_charges);

        var b_c_shipping_charges = parseFloat($('#b_c_shipping_charges_' + number).val());
        b_c_shipping_charges = Nancheck(b_c_shipping_charges);

        var remittance_charges = parseFloat($('#remittance_charges_' + number).val());
        remittance_charges = Nancheck(remittance_charges);

        var other_bank_charges = parseFloat($('#other_bank_charges_' + number).val());
        other_bank_charges = Nancheck(other_bank_charges);

        var insurance_exp = parseFloat($('#insurance_exp_' + number).val());
        insurance_exp = Nancheck(insurance_exp);

        var custome_duty = parseFloat($('#custome_duty_' + number).val());
        custome_duty = Nancheck(custome_duty);

        var additional_custom_duty = parseFloat($('#additional_custom_duty_' + number).val());
        additional_custom_duty = Nancheck(additional_custom_duty);

        var excise_taxation = parseFloat($('#excise_taxation_' + number).val());
        excise_taxation = Nancheck(excise_taxation);

        var whage_godown_charges = parseFloat($('#whage_godown_charges_' + number).val());
        whage_godown_charges = Nancheck(whage_godown_charges);

        var air_freight = parseFloat($('#air_freight_' + number).val());
        air_freight = Nancheck(air_freight);

        var cost_in_pkr = parseFloat($('#cost_in_pkr_' + number).val());
        cost_in_pkr = Nancheck(cost_in_pkr);

        var total = (b_c_opening_charges + b_c_shipping_charges + remittance_charges + other_bank_charges + insurance_exp + custome_duty +
            additional_custom_duty + excise_taxation + whage_godown_charges + air_freight + cost_in_pkr).toFixed(2);
        $('#total_landed_' + number).val(total);
        total = parseFloat(total);


        var qty = parseFloat($('#total_qty_' + number).val());
        var land_cost_qty = (total / qty).toFixed(2);
        $('#land_cost_qty_' + number).text(land_cost_qty);
        var landerd_per_pac_cost = parseFloat(land_cost_qty * 10).toFixed(2);
        $('.landed_cost_per_pack_' + number).text(landerd_per_pac_cost);
        $('.landed_cost_per_item_' + number).text(land_cost_qty);

        var sales_tax = parseFloat($('#sales_tax_' + number).val());
        sales_tax = Nancheck(sales_tax);

        var income_tax = parseFloat($('#income_tax_' + number).val());
        income_tax = Nancheck(income_tax);



        var total_cash_flow = (sales_tax + income_tax + total);


        $('#total_cash_flow_' + number).val(total_cash_flow);



    }


    function costing_per_pac_cost(id, number) {
        var sachet_foli_per_pack = parseFloat($('#sachet_foli_per_pack_' + number).val());
        sachet_foli_per_pack = Nancheck(sachet_foli_per_pack);

        var uniit_carton_per_pack = parseFloat($('#uniit_carton_per_pack_' + number).val());
        uniit_carton_per_pack = Nancheck(uniit_carton_per_pack);

        var leaf_insert_per_pack = parseFloat($('#leaf_insert_per_pack_' + number).val());
        leaf_insert_per_pack = Nancheck(leaf_insert_per_pack);

        var master_carton_per_pack = parseFloat($('#master_carton_per_pack_' + number).val());
        master_carton_per_pack = Nancheck(master_carton_per_pack);

        var packing_cahrges_per_pack = parseFloat($('#packing_cahrges_per_pack_' + number).val());
        packing_cahrges_per_pack = Nancheck(packing_cahrges_per_pack);

        var total_per_pack_cost = parseFloat($('.landed_cost_per_pack_' + number).text());

        var total = (sachet_foli_per_pack + uniit_carton_per_pack + leaf_insert_per_pack + master_carton_per_pack + packing_cahrges_per_pack + total_per_pack_cost).toFixed(2);
        $('.per_pack_cost_' + number).text(total);
    }


    function costing_per_pac_item(id, number) {
        var sachet_foli_per_item = parseFloat($('#sachet_foli_per_item_' + number).val());
        sachet_foli_per_item = Nancheck(sachet_foli_per_item);

        var unit_carton_per_item = parseFloat($('#unit_carton_per_item_' + number).val());
        unit_carton_per_item = Nancheck(unit_carton_per_item);

        var leaf_insert_per_item = parseFloat($('#leaf_insert_per_item_' + number).val());
        leaf_insert_per_item = Nancheck(leaf_insert_per_item);

        var master_carton_per_item = parseFloat($('#master_carton_per_item_' + number).val());
        master_carton_per_item = Nancheck(master_carton_per_item);

        var packing_cahrges_per_item = parseFloat($('#packing_cahrges_per_item_' + number).val());
        packing_cahrges_per_item = Nancheck(packing_cahrges_per_item);

        var total_per_pack_item = parseFloat($('.landed_cost_per_item_' + number).text());

        var total = (sachet_foli_per_item + unit_carton_per_item + leaf_insert_per_item + master_carton_per_item + packing_cahrges_per_item + total_per_pack_item).toFixed(2);
        $('.per_item_cost_' + number).text(total);
    }


    function discount_percent(id) {
        var number = id.replace("discount_percent", "");
        var amount = $('#amount' + number).val();

        var x = parseFloat($('#' + id).val());

        if (x > 100) {
            alert('Percentage Cannot Exceed by 100');
            $('#' + id).val(0);
            x = 0;
        }

        x = x * amount;
        var discount_amount = parseFloat(x / 100).toFixed(2);
        $('#discount_amount' + number).val(discount_amount);
        var discount_amount = $('#discount_amount' + number).val();

        if (isNaN(discount_amount)) {

            $('#discount_amount' + number).val(0);
            discount_amount = 0;
        }



        var amount_after_discount = amount - discount_amount;

        $('#after_dis_amountt_' + number).val(amount_after_discount);
        var amount_after_discount = $('#after_dis_amountt_' + number).val();

        //        if (amount_after_discount==0)
        //        {
        //            $('#net_amount'+number).val(amount);
        //            $('#net_amounttd_'+number).val(amount);
        //            $('#net_amount_'+number).val(amount_after_discount);
        //        }
        //
        //        else
        //        {
        //            $('#net_amounttd_'+number).val(amount_after_discount);
        //            $('#net_amount_'+number).val(amount_after_discount);
        //        }
        //
        //        $('#cost_center_dept_amount'+number).text(amount_after_discount);
        //        $('#cost_center_dept_hidden_amount'+number).val(amount_after_discount);


        //sales_tax('sales_taxx');

        //toWords(1);


    }
    function calculation(number) {
        //  var number=  id.replace("carton_", "");




        var approve_qty = parseFloat($('#approved_qty_' + number).val());
        var rec_qty = parseFloat($('#rec_qty_' + number).val());
        var total = (approve_qty - rec_qty).toFixed(2);
        $('#balqty_' + number).val(total);


        if (rec_qty > approve_qty) {
            $('#balqty_' + number).delay(3000).css("background-color", "yellow");
        }
        else {
            $('#balqty_' + number).delay(3000).css("background-color", "");
        }

        //ABDUL
        //var  qty=$('#purchase_approve_qty_'+number).val();
        var rate = $('#rate' + number).val();
        var currency = $('#currency').val();


        var total = parseFloat(rec_qty * rate * currency).toFixed(2);

        $('#amount' + number).val(total);

        var amount = 0;
        count = 1;
        $('.net_amount_dis').each(function () {


            amount += +$('#after_dis_amountt_' + count).val();
            count++;
        });
        amount = parseFloat(amount);


        //sales_tax('sales_taxx');
        discount_percent('discount_percent' + number);
        //toWords(1);
        //ABDUL

    }


    function import_costing(id, number) {
        if ($('#' + id).is(":checked")) {
            $('.' + id).fadeIn(500);
            var total_qty = $('#total_qty_' + number).val();
            $('#Sachets_' + number).text(total_qty);
            var amount = $('#amount_' + number).val();

        }
        else {
            $('.' + id).fadeOut(500);
        }
    }
    function Nancheck(value) {

        if (isNaN(value)) {
            value = 0;
        }

        return value;
    }

</script>

<script type="text/javascript">

    $('#warehouse_id').select2();

</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>