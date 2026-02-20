<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$MenuPermission = true;


$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

if ($accType == 'user'):
    $user_rights = DB::table('menu_privileges')->where([['emp_code', '=', Auth::user()->emp_code], ['compnay_id', '=', Session::get('run_company')]]);
    $submenu_ids = explode(",", $user_rights->value('submenu_id'));
    if (in_array(81, $submenu_ids)) {
        $MenuPermission = true;
    } else {
        $MenuPermission = false;
    }
endif;

?>

@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    @include('number_formate')


    <script>
        var counter = 1;
    </script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="headquid">
                            <h2 class="subHeadingLabelClass">Create Direct Purchase Voucher Form</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <?php
    if ($MenuPermission == true):?>
                                <?php else:?>
                                <span class="subHeadingLabelClass text-danger text-center" style="float: right">Permission
                                    Denied <span style='font-size:45px !important;'>&#128546;</span></span>
                                <?php endif;

                                            ?>
                            </div>
                        </div>
                        <?php if ($MenuPermission == true):?>
                        <div class="lineHeight">&nbsp;</div>


                        <?php    echo Form::open(array('url' => 'pad/insertDirectPurchaseVoucher?m=' . $m . '', 'id' => 'addDirectPurchaseVoucherDetailNew', 'class' => 'stop'));?>
                        <?php


        $purchaseRequestNo = CommonHelper::get_unique_po_no_with_status(1);
                                    ?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <input type="hidden" name="pageType" value="<?php    echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php    echo $_GET['parentCode']?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">PV NO.</label>
                                                <input readonly type="text" class="form-control requiredField"
                                                    placeholder="" name="po_no" id="po_no"
                                                    value="{{strtoupper($purchaseRequestNo)}}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">PV DATE.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField"
                                                    max="<?php    echo date('Y-m-d') ?>" name="po_date" id="po_date"
                                                    value="<?php    echo date('Y-m-d') ?>" />
                                            </div>



                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hide">
                                                <label class="sf-label">Department / Sub Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control  select2" name="sub_department_id_1"
                                                    id="sub_department_id_1">
                                                    <option value="0">Select Department</option>
                                                    @foreach($departments as $key => $y)
                                                                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                                                    <?php
                                                        $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where  `department_id` =' . $y->id . '');
                                                                                                                                                                                                                                                                                                    ?>
                                                                                                    @foreach($subdepartments as $key2 => $y2)
                                                                                                        <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                                                    @endforeach
                                                                                                </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mode of delivery</label>
                                                <input type="text" class="form-control" placeholder="Terms Of Delivery"
                                                    name="term_of_del" id="term_of_del" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">PV Type</label>
                                                <select onchange="get_po(this.id)" name="po_type" id="po_type"
                                                    class="form-control">
                                                    <option value="1">Purchase Local</option>
                                                    <option value="2">Internal Use</option>
                                                    <option value="3">International</option>
                                                </select>
                                            </div>

                                        </div>


                                        <div class="row">


                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Destination</label>
                                                <input style="text-transform: capitalize;" type="text" class="form-control"
                                                    placeholder="" name="destination" id="destination" value="" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#"
                                                        onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');"
                                                        class="">Vendor</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_address()" name="supplier_id" id="supplier_id"
                                                    class="form-control requiredField select2">
                                                    <option value="">Select Vendor</option>
                                                    <?php
        foreach ($supplierList as $row1) {

            $address = CommonHelper::get_supplier_address($row1->id);
                                                                ?>
                                                    <option
                                                        value="<?php        echo $row1->id . '@#' . $address . '@#' . $row1->ntn . '@#' . $row1->terms_of_payment?>">
                                                        <?php        echo ucwords($row1->name)?>
                                                    </option>
                                                    <?php
        }
                                                                ?>
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#"
                                                        onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')"
                                                        class="">Currency</a></label>
                                                <span class="rflabelsteric"></span>
                                                <select onchange="claculation(1);get_rate()" name="curren" id="curren"
                                                    class="form-control select2 requiredField">
                                                    {{-- <option value="0,1"> PKR</option> --}}
                                                    <option value=""> Select Currency</option>

                                                    @foreach(CommonHelper::get_all_currency_with_current_rate() as $row)
                                                        <option value="{{$row->id . ',' . $row->rate}}">{{$row->name}}</option>
                                                    @endforeach;

                                                </select>

                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> Currency Rate</label>
                                                <span class="rflabelsteric"></span>
                                                <input class="form-control" type="text" name="currency_rate"
                                                    id="currency_rate" />

                                            </div>

                                            <input type="hidden" name="curren_rate" id="curren_rate" value="1" />

                                        </div>

                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mode/ Terms Of Payment <span
                                                        class="rflabelsteric"></span></label>
                                                <input onkeyup="calculate_due_date()" value="0" type="number"
                                                    class="form-control requiredField" placeholder=""
                                                    name="model_terms_of_payment" id="model_terms_of_payment" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Payment Due Date<span
                                                        class="rflabelsteric"></span></label>
                                                <input type="date" class="form-control" placeholder="" name="due_date"
                                                    id="due_date" value="" readonly />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Supplier's Address</label>
                                                <input style="text-transform: capitalize;" readonly type="text"
                                                    class="form-control" placeholder="" name="address" id="addresss"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>

                                        <div class="row">



                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Supplier's NTN</label>
                                                <input readonly type="text" class="form-control" placeholder="Ntn"
                                                    name="ntn" id="ntn_id" value="" />
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Remarks</label>
                                                <textarea name="Remarks" id="terms_and_condition" class="form-control"
                                                    placeholder="Remarks"></textarea>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" hidden>
                                                <label class="sf-label">Agent</label>
                                                <span class="rflabelsteric"></span>
                                                <select onchange="get_data()" class="form-control" name="agent" id="agent">
                                                    <option value="">Select</option>
                                                    @foreach(Commonhelper::get_all_sub_department() as $key2 => $row)
                                                        <option value="{{ $row->id}}">{{ $row->sub_department_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" hidden>
                                                <label class="sf-label">Commission rate per carton</label>
                                                <input type="text" name="commission" id="commission" class="form-control "
                                                    placeholder="">
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hide">
                                                <label class="sf-label">TRN<span class="rflabelsteric"></span></label>
                                                <input type="text" value="0" name="trn" id="trn" class="form-control"
                                                    placeholder="TRN">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hide">
                                                <label class="sf-label">Builty No</label>
                                                <input type="text" name="builty_no" id="builty_no" class="form-control "
                                                    placeholder="Builty No">
                                            </div>


                                        </div>



                                    </div>
                                    {{-- <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Terms & Condition</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="main_description" id="main_description" rows="4" cols="50"
                                                    style="resize:none;font-size: 11px;" class="form-control requiredField">YOUR NTN NUMBER AND VALID INCOME TAX EXEMPTION WILL BE REQUIRED FOR PAYMENT, OTHER WISE INCOME TAX WILL BE DEDUCTED AS PER FOLLOWINGS:
                    INCOME TAX:
                    FOR COMPANIES SUPPLIES 4% & SERVICES 8% (FILER) / 12% (NON FILER)
                    FOR INDIVIUALS OR AOP SUPPLIES 4.5% & SERVICES 10% (FILER) / 15% (NON FILER)
                    SALES TAX ON SUPPLIES:
                    A WITHOLDING AGENT SHALL DEDUCT AN AMOUNT AS PER SRO 897 /2013
                    SALES TAX ON SERVICES:
                    A WITHOLDING AGENT SHALL DEDUCT AN AMOUNT AS PER SRB WITHHOLDING RULES-2014</textarea>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th colspan="11" class="text-center">Purchase Invoice Detail</th>
                                                        {{-- <th colspan="2" class="text-center">
                                                            <input type="button" class="btn btn-sm btn-primary"
                                                                onclick="AddMoreDetails()" value="Add More Rows" />
                                                        </th> --}}
                                                        <th class="text-center">
                                                            <span class="badge badge-success" id="span">1</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 10%;">Category</th>
                                                        <th class="text-center" style="width: 12%;">Item</th>
                                                        <th class="text-center">Uom<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        {{-- <th class="text-center">HS Code<span
                                                                class="rflabelsteric"><strong>*</strong></span></th> --}}
                                                        <th class="text-center">Bags Qty<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Qty in KG<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">QTY (lbs)<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Rate<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Amount(PKR)<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center"> Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        {{-- <th class="text-center">Discount %<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Discount Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th> --}}
                                                        <th class="text-center">Net Amount(PKR)<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Location<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Action</th>
                                                        {{-- <th class="text-center">Delete<span
                                                                class="rflabelsteric"><strong>*</strong></span></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                    <tr title="1" class="AutoNo">
                                                        <td>
                                                            <select style="width: 100% !important;"
                                                                onchange="get_sub_item('category_id1')" name="category[]"
                                                                id="category_id1"
                                                                class="form-control category select2 requiredField">
                                                                <option value="">Select</option>
                                                                @foreach (CommonHelper::get_all_category() as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->main_ic }} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select style="width: 100% !important;"
                                                                onchange="get_item_name(1)" name="item_id[]" id="item_id1"
                                                                class="form-control requiredField select2">
                                                                <option>Select</option>
                                                            </select>
                                                        </td>
                                                        {{-- <td>
                                                            <select name="item_id[]" id="item_1" onchange="itemChange(1)"
                                                                class="form-control select2">
                                                                <option value="">Select</option>
                                                                @foreach (CommonHelper::get_all_subitem() as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-hscode="{{CommonHelper::hs_code_name($item->hs_code_id)}}"
                                                                    data-uom="{{$item->uom_name}}"
                                                                    data-pack_size="{{ $item->pack_size ?? 1}}">
                                                                    {{ $item->sub_ic }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td> --}}

                                                        <td>
                                                            <input readonly type="text" class="form-control" name="uom_id[]"
                                                                id="uom_id1">
                                                        </td>
                                                        {{-- <td>
                                                            <input readonly type="text" class="form-control"
                                                                name="hs_code_id[]" id="hs_code_id1">
                                                        </td> --}}
                                                        <td>
                                                            <input type="text" class="form-control requiredField BagsQty"
                                                                name="bags_qty[]" id="bags_qty1" value="1" min="1"
                                                                oninput="bag_qq(1)">
                                                        </td>
                                                        <td>
                                                            <input type="text" onchange="claculation('1')"
                                                                class="form-control requiredField ActualQty"
                                                                name="actual_qty[]" id="actual_qty1"
                                                                placeholder="ACTUAL QTY" min="1" value="" readonly>
                                                            <input type="hidden" class="PackQty" name="pack_qty[]"
                                                                id="pack_qty">
                                                        </td>
                                                        <td>
                                                            <input class="form-control requiredField" type="number"
                                                                id="qty_lbs1" name="qty_lbs[]" step="any" readonly />
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="claculation('1')"
                                                                class="form-control requiredField ActualRate" name="rate[]"
                                                                id="rate1" placeholder="RATE" min="1" value="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control number_format"
                                                                name="amount[]" id="amount1" placeholder="AMOUNT" min="1"
                                                                value="" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control actual_amount number_format"
                                                                name="actual_amount[]" id="actual_amount1"
                                                                placeholder="AMOUNT" min="1" value="" readonly>
                                                        </td>

                                                        <input type="hidden" onkeyup="discount_percent(this.id)"
                                                            class="form-control " name="discount_percent[]"
                                                            id="discount_percent1" placeholder="DISCOUNT" min="1" value="0">

                                                        <input type="hidden" onkeyup="discount_amount(this.id)"
                                                            class="form-control " name="discount_amount[]"
                                                            id="discount_amount1" placeholder="DISCOUNT" min="1" value="0">

                                                        <td>
                                                            <input type="text"
                                                                class="form-control net_amount_dis number_format"
                                                                name="after_dis_amount[]" id="after_dis_amount1"
                                                                placeholder="NET AMOUNT" min="1" value="0.00" readonly>
                                                        </td>
                                                        <td>
                                                            <select required class="form-control select2"
                                                                name="warehouse_id[]" id="warehouse_1">
                                                                <option value="">Select</option>
                                                                @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        {{-- <td style="background-color: #ccc">
                                                            <input onclick="view_history(1)" type="checkbox"
                                                                id="view_history1">
                                                        </td> --}}
                                                        <td style="background-color: #ccc" class="text-center">
                                                            <input type="button" class="btn btn-sm btn-primary"
                                                                onclick="AddMoreDetails()" value="+" />
                                                        </td>

                                                    </tr>
                                                </tbody>

                                                <tbody>
                                                    <tr style="font-size:large;font-weight: bold">
                                                        <td class="text-center" colspan="5">Total</td>
                                                        <td class="text-right" colspan="1"><input readonly
                                                                class="form-control number_format" type="text"
                                                                name="pkr_net" id="pkr_net" /> </td>
                                                        <td class="text-right" colspan="1"><input readonly
                                                                class="form-control number_format" type="text"
                                                                name="actual_net" id="actual_net" /> </td>
                                                        <td class="text-right" colspan="1"><input readonly
                                                                class="form-control number_format" type="text" id="net"
                                                                name="net" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>



                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                                        <table class="table table-bordered sf-table-list">
                                            <thead>
                                                <th class="text-center" colspan="3">Sales Tax Account Head</th>
                                                <th class="text-center" colspan="3">Sales Tax Amount</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select onchange="sales_tax(this.id)" class="form-control select2"
                                                            id="sales_taxx" name="sales_taxx">
                                                            <option value="0">Select</option>
                                                            @foreach (ReuseableCode::get_all_sales_tax() as $row)
                                                                <option value="{{ $row->percent . '@' . $row->acc_id }}"
                                                                    {{($row->percent == "17.000") ? 'selected' : ''}}>
                                                                    {{ $row->percent }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-right" colspan="3">
                                                        <input onkeyup="tax_by_amount(this.id)" type="text"
                                                            class="form-control number_format" name="sales_amount_td"
                                                            id="sales_amount_td" />
                                                    </td>
                                                    <input type="hidden" name="sales_amount" id="sales_tax_amount" />
                                                </tr>


                                            </tbody>

                                            <tbody>
                                                <tr style="font-size:large;font-weight: bold">
                                                    <td class="text-center" colspan="3">Total Amount After Tax(PKR)</td>
                                                    <td id="" class="text-right" colspan="3"><input readonly
                                                            class="form-control number_format" type="text"
                                                            name="net_after_tax" id="net_after_tax" /> </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <table>
                                    <tr>

                                        <td style="text-transform: capitalize;" id="rupees"></td>
                                        <input type="hidden" value="" name="rupeess" id="rupeess1" />
                                    </tr>
                                </table>
                                <input type="hidden" id="d_t_amount_1" />

                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php    echo Form::close();?>
                    <?php endif;?>
                </div>

            </div>
        </div>
    </div>
    <script>

        var Counter = 1;

        function AddMoreDetails() {
            Counter++;
            var category = 'category_id' + Counter;

            $('#AppnedHtml').append('<tr id="RemoveRows' + Counter + '" class="AutoNo">' +
                // '<td class="AutoCounter" title="' + AutoCount + '">' +
                //  '<select name="item_id[]" id="item_' + Counter + '" onchange="itemChange(' + Counter + ')"' +
                //  'class="form-control select2">' +
                //  '<option value="">Select</option>' +
                // '@foreach (CommonHelper::get_all_subitem() as $item)' +
                    //      '<option value="{{ $item->id }}"  data-hscode="{{CommonHelper::hs_code_name($item->hs_code_id)}}" data-uom="{{$item->uom_name}}" data-pack_size="{{ $item->pack_size ?? 1 }}">' +
                    //      '{{ $item->sub_ic }}' +
                    //      '</option>' +
                //  '@endforeach' +
                //  '</select>' +
                //  '</td>' +

                '<td>' +
                '<select style="width: 100% !important;" onchange="get_sub_item(`' + category + '`)" name="category[]" id="category_id' + Counter + '"  class="form-control category select2">' +
                '<option value="">Select</option>' +
                '@foreach (CommonHelper::get_all_category() as $category):' +
                    '<option value="{{ $category->id }}"> {{ $category->main_ic }} </option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select style="width: 100% !important;" onchange="get_item_name(' + Counter + ')" name="item_id[]" id="item_id' + Counter + '" class="form-control select2">' +
                '<option>Select</option>' +
                '</select>' +
                '</td>' +

                '<td>' +
                '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id' + Counter + '" >' +
                '</td>' +
                // '<td>' +
                // '<input readonly type="text" class="form-control" name="hs_code_id[]" id="hs_code_id' + Counter + '" >' +
                // '</td>' +
                '<td>' +
                '<input type="text" onkeyup="bag_qq(' + Counter + ')" class="form-control requiredField BagsQty" name="bags_qty[]" id="bags_qty' + Counter + '" placeholder="BAGS QTY">' +
                '</td>' +
                '<td>' +
                '<input type="text" onchange="claculation(' + Counter + ')" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty' + Counter + '" placeholder="ACTUAL QTY" readonly>' +
                '<input type="hidden" ' +
                'class="PackQty" ' +
                'name="pack_qty[]" ' +
                'id="pack_qty">' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control requiredField" name="qty_lbs[]" id="qty_lbs' + Counter + '" placeholder="QTY LBS">' +
                '</td>' +
                '<td>' +
                '<input type="text" onkeyup="claculation(' + Counter + ')" class="form-control requiredField ActualRate" name="rate[]" id="rate' + Counter + '" placeholder="RATE">' +
                '</td>' +
                '<td>' +
                '<input readonly type="text" class="form-control number_format" name="amount[]" id="amount' + Counter + '" placeholder="AMOUNT">' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control actual_amount number_format" name="actual_amount[]" id="actual_amount' + Counter + '" placeholder="AMOUNT" min="1" value="" readonly>' +
                '</td>' +

                '<input type="hidden" onkeyup="discount_percent(this.id)" class="form-control " value="0" name="discount_percent[]" id="discount_percent' + Counter + '" placeholder="DISCOUNT">' +

                '<input type="hidden" onkeyup="discount_amount(this.id)" class="form-control " value="0" name="discount_amount[]" id="discount_amount' + Counter + '" placeholder="DISCOUNT">' +

                '<td>' +
                '<input readonly type="text" class="form-control net_amount_dis number_format" name="after_dis_amount[]" id="after_dis_amount' + Counter + '" placeholder="NET AMOUNT">' +
                '</td>' +
                '<td>' +
                '<select name="warehouse_id[]" id="warehouse_' + Counter + '"' +
                'class="form-control select2">' +
                '<option value="">Select</option>' +
                '@foreach (CommonHelper::get_all_warehouse() as $row)' +
                    '<option value="{{ $row->id }}">' +
                    '{{ $row->name }}' +
                    '</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td style="background-color: #ccc" class="text-center">' +
                //'<input onclick="view_history(' + Counter + ')" type="checkbox" id="view_history' + Counter + '">&nbsp;' +
                '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove' + Counter + '" onclick="RemoveSection(' + Counter + ')"> - </button>' +
                '</td>' +
                '</tr>');

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            $('.select2').select2();
            $('.number_format').number(true, 2);
            var AutoCount = 1;;
            $(".AutoCounter").each(function () {
                AutoCount++;
                $(this).prop('title', AutoCount);

            });
        }
        function RemoveSection(Row) {
            //            alert(Row);
            $('#RemoveRows' + Row).remove();
            //   $(".AutoCounter").html('');
            var AutoCount = 1;
            var AutoCount = 1;;
            $(".AutoCounter").each(function () {
                AutoCount++;
                $(this).prop('title', AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            //discount_percent('discount_percent' + Row);
            net_amount();
            sales_tax('sales_taxx');
        }

        function get_po(id) {
            var number = $('#' + id).val();

            var po = $('#po_no').val();
            if (number == 1) {
                var res = po.slice(2, 9);
                var pl_no = 'PL' + res;
                $('#po_no').val(pl_no);

            }
            if (number == 2) {
                var res = po.slice(2, 9);
                var pl_no = 'PS' + res;
                $('#po_no').val(pl_no);

            }
            if (number == 3) {
                var res = po.slice(2, 9);
                var pl_no = 'PI' + res;
                $('#po_no').val(pl_no);

            }

        }
    </script>
    <script>
        var x = 0;





        function tax_by_amount(id) {


            var tax_percentage = $('#sales_taxx').val();



            if (tax_percentage == 0) {

                $('#' + id).val(0);
            }
            else {
                var tax_amount = parseFloat($('#' + id).val());

                // highlight end

                if (isNaN(tax_amount) == true) {
                    tax_amount = 0;
                }
                var count = 1;
                var amount = 0;
                $('.net_amount_dis').each(function () {


                    amount += +$('#after_dis_amountt_' + count).val();
                    count++;
                });
                var total = parseFloat(tax_amount + amount).toFixed(2);
                $('#d_t_amount_1').val(total);


            }
            //            toWords(1);



        }

        function net_amount() {
            var amount = 0;
            var actual_amount = 0;
            var pkr_amount = 0;
            $('.net_amount_dis').each(function (i, obj) {

                amount += +$('#' + obj.id).val();
            });
            $('.actual_amount').each(function (i, obj) {

                actual_amount += +$('#' + obj.id).val();
            });
            $('input[name="amount[]"]').each(function (i, obj) {
                pkr_amount += +$('#' + obj.id).val();
            });
            amount = parseFloat(amount);
            actual_amount = parseFloat(actual_amount);
            pkr_amount = parseFloat(pkr_amount);
            $('#net').val(amount);
            $('#actual_net').val(actual_amount);
            $('#pkr_net').val(pkr_amount);
            var sales_tax = parseFloat($('#sales_amount_td').val());

            $('#net_after_tax').val(amount + sales_tax);
            $('#d_t_amount_1').val(amount + sales_tax);
            toWords(1);

        }



        function view_history(id) {

            var v = $('#sub_' + id).val();


            if ($('#view_history' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
                }
                else {
                    alert('Select Item');
                }

            }





        }





        $(document).ready(function () {

            for (i = 1; i <= counter; i++) {
                $('#amount_' + i).number(true, 2);
                //   $('#rate_'+i).number(true,2);
                $('#purchase_approve_qty_' + i).number(true, 2);


                $('#after_dis_amountt' + i).number(true, 2);
                $('#rate_' + i).number(true, 2);
            }

            $('#d_t_amount_1').number(true, 2);
            $('#sales_amount_td').number(true, 2);

            $(".btn-success").click(function (e) {
                //alert();
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        //alert(response);
                        vala = 0;
                        var flag = false;
                        $('.ActualQty').each(function () {
                            vala = parseFloat($(this).val());
                            if (vala == 0) {
                                alert('Please Enter Correct Actual Qty....!');
                                $(this).css('border-color', 'red');
                                flag = true;
                                return false;
                            }
                            else {
                                $(this).css('border-color', '#ccc');
                            }
                        });

                        $('.ActualRate').each(function () {
                            vala = parseFloat($(this).val());
                            if (vala == 0) {
                                alert('Please Enter Correct Rate Qty....!');
                                $(this).css('border-color', 'red');
                                flag = true;
                                return false;
                            }
                            else {
                                $(this).css('border-color', '#ccc');
                            }
                        });
                        if (flag == true) { return false; }
                    } else {
                        return false;
                    }
                }

            });


            $(document).keypress("m", function (e) {
                if (e.ctrlKey)
                    AddMoreDetails();
            });
            $('.number_format').number(true, 2);
        });
        function removeSeletedPurchaseRequestRows(id, counter) {
            var totalCounter = $('#totalCounter').val();
            if (totalCounter == 1) {
                alert('Last Row Not Deleted');
            } else {
                var lessCounter = totalCounter - 1;
                var totalCounter = $('#totalCounter').val(lessCounter);
                var elem = document.getElementById('removeSelectedPurchaseRequestRow_' + counter + '');
                elem.parentNode.removeChild(elem);
            }

        }



        $(document).ready(function () {
            //            toWords(1);
        });

        function bag_qq(counter) {
            var bags_qty = parseFloat($('#bags_qty' + counter).val()) || 1;
            var pack_qty = parseFloat($('#pack_qty').val()) || 0;

            var total_qty = (bags_qty * pack_qty).toFixed(2);
            $('#actual_qty' + counter).val(total_qty);
            $('#qty_lbs' + counter).val(total_qty * 2.20462);

        }

        function claculation(number) {
            var qty = $('#actual_qty' + number).val();
            var rate = $('#rate' + number).val();
            var currency = $('#currency_rate').val();
            var qty_lbs = parseFloat(qty) * 2.20462 || 0;

            var actual = parseFloat(qty_lbs * rate).toFixed(2);
            if (currency == '') {
                currency = 1;
            }

            var total = parseFloat(qty_lbs * rate * currency).toFixed(2);

            $('#amount' + number).val(total);
            $('#actual_amount' + number).val(actual);
            

            var amount = 0;
            count = 1;
            $('.net_amount_dis').each(function (i, obj) {

                amount += +$('#' + obj.id).val();

                count++;
            });
            amount = parseFloat(amount);



            discount_percent('discount_percent' + number);
            net_amount();
            sales_tax('sales_taxx');
            //  toWords(1);
        }
        function sales_tax(id) {
            var sales_tax = 0;
            var sales_tax_per_value = $('#sales_taxx').val();
            sales_tax_per_value = sales_tax_per_value.split("@")[0];

            if (sales_tax_per_value != '0') {
                var net = $('#net').val();

                var sales_tax = (net / 100) * sales_tax_per_value;

            }
            console.log(sales_tax)
            $('#sales_amount_td').val(sales_tax);

            total_amount();

        }
        function total_amount() {
            var amount = 0;

            $('.net_amount_dis').each(function () {

                amount += +$(this).val();

            });
            $('#net').val(amount);

            var sales_tax = parseFloat($('#sales_amount_td').val());
            var net = (amount + sales_tax).toFixed(2);

            $('#net_after_tax').val(net);
            console.log(net);


        }

        function get_item_name(index) {

            var item = $('#item_id' + index).val();
            var uom = item.split('@');
            console.log(uom);
            $('#uom_id' + index).val(uom[1]);
            $('#item_code' + index).val(uom[2]);
            $('#actual_qty' + index).val(uom[3]);
            $('#qty_lbs' + index).val(uom[3] * 2.20462);
            $('#pack_qty').val(uom[3]);

            if (!$('#bags_qty' + index).val()) {
                $('#bags_qty' + index).val(1);
            }

            bag_qq(index);
        }

        function get_address() {
            var supplier = $('#supplier_id').val();

            supplier = supplier.split('@#');
            $('#addresss').val(supplier[1]);

            $('#ntn_id').val(supplier[2]);
            $('#model_terms_of_payment').val(supplier[3]);
            calculate_due_date();
        }


        function get_rate() {
            var currency_id = $('#curren').val();
            currency_id = currency_id.split(',');
            console.log(currency_id);
            $('#currency_rate').val(currency_id[1]);
            $('#curren_rate').val(currency_id[1]);
        }
    </script>
    <script>




        function open_sales_tax(id) {

            var dept_name = $('#' + id + ' :selected').text();


            if (dept_name == 'Add New') {

                showDetailModelOneParamerter('fdc/createAccountFormAjax/sales_taxx')
            }

        }



        function discount_percent(id) {
            var number = id.replace("discount_percent", "");
            // var amount = $('#actual_amount' + number).val();
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

            $('#after_dis_amount' + number).val(amount_after_discount);
            var amount_after_discount = $('#after_dis_amount' + number).val();

            if (amount_after_discount == 0) {
                $('#after_dis_amount' + number).val(amount);
                $('#net_amounttd_' + number).val(amount);
                $('#net_amount' + number).val(amount_after_discount);
            }

            else {

                $('#net_amounttd_' + number).val(amount_after_discount);
                $('#after_dis_amount' + number).val(amount_after_discount);
            }

            $('#cost_center_dept_amount' + number).text(amount_after_discount);
            $('#cost_center_dept_hidden_amount' + number).val(amount_after_discount);


            sales_tax('sales_taxx');
            net_amount();
            //  toWords(1);


        }


        function discount_amount(id) {
            var number = id.replace("discount_amount", "");
            var amount = parseFloat($('#amount' + number).val());

            var discount_amount = parseFloat($('#' + id).val());

            if (discount_amount > amount) {
                alert('Amount Cannot Exceed by ' + amount);
                $('#discount_amount' + number).val(0);
                discount_amount = 0;
            }

            if (isNaN(discount_amount)) {

                $('#discount_amount' + number).val(0);
                discount_amount = 0;
            }

            var percent = (discount_amount / amount * 100).toFixed(2);
            $('#discount_percent' + number).val(percent);
            var amount_after_discount = amount - discount_amount;
            $('#after_dis_amount' + number).val(amount_after_discount);


            $('#net_amounttd_' + number).val(amount_after_discount);
            $('#net_amount_' + number).val(amount_after_discount);
            sales_tax('sales_taxx');
            //   toWords(1);
            net_amount();


        }


        function get_detail(id, number) {
            var item = $('#' + id).val();


            $.ajax({
                url: '{{url('/pdc/get_data')}}',
                data: { item: item },
                type: 'GET',
                success: function (response) {

                    var data = response.split(',');
                    $('#uom_id' + number).val(data[0]);


                }
            })



        }
        $(".remove").each(function () {

            $(this).html($(this).html().replace(/,/g, ''));
        });

        function calculate_due_date() {

            //            var days=parseFloat($('#model_terms_of_payment').val());
            //
            //            var tt = document.getElementById('po_date').value;
            //
            //
            //            var date = new Date(tt);
            //            var newdate = new Date(date);
            //            newdate.setDate(newdate.getDate() + days);
            //            var dd = newdate.getDate();
            //
            //
            //            var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
            //
            //            var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
            //            var y = newdate.getFullYear();
            //            var someFormattedDate =  + y+'-'+ mm +'-'+dd;
            //
            //            document.getElementById('due_date').value = someFormattedDate;

            var date = new Date($("#po_date").val());
            var days = parseFloat($('#model_terms_of_payment').val());
            days = days;

            if (!isNaN(date.getTime())) {
                date.setDate(date.getDate() + days);


                var yyyy = date.getFullYear().toString();
                var mm = (date.getMonth() + 1).toString(); // getMonth() is zero-based
                var dd = date.getDate().toString();
                var new_d = yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]);


                $("#due_date").val(new_d);
            } else {
                alert("Invalid Date");
            }


        }
        function itemChange(id) {
            $('#uom_id' + id).val($('#item_' + id).find(':selected').data("uom"));
            $('#hs_code_id' + id).val($('#item_' + id).find(':selected').data("hscode"));
            $('#actual_qty' + id).val($('#item_' + id).find(':selected').data("pack_size"));
            var packSize = $('#item_' + id).find(':selected').data('pack_size') || 0;
            $('#pack_qty').val(packSize);

            // Default bags qty to 1
            if (!$('#bags_qty' + id).val()) {
                $('#bags_qty' + id).val(1);
            }

            // Calculate actual qty
            bag_qq(id);
        }
    </script>




    <script type="text/javascript">
        $('.select2').select2();

    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection