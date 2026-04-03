<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
use Carbon\Carbon;
$MenuPermission = true;

$accType = Auth::user()->acc_type;
$m=Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

$startDate = Carbon::parse($NewPurchaseVoucher->bill_date);
$endDate = Carbon::parse($NewPurchaseVoucher->due_date);
$model_terms_of_payment = $endDate->diffInDays($startDate);


$totalItemRows = count($NewPurchaseVoucherData);

if ($accType == 'user'):
    $user_rights = DB::table('menu_privileges')->where([['emp_code', '=', Auth::user()->emp_code], ['compnay_id', '=', Session::get('run_company')]]);
    $submenu_ids = explode(',', $user_rights->value('submenu_id'));
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

    <style>
        .table-compact {
            font-size: 0.92rem;
            line-height: 1.15;
        }

        .table-compact th,
        .table-compact td {
            padding: 5px 6px !important;
            vertical-align: middle;
        }

        .table-compact input.form-control,
        .table-compact select.form-control {
            font-size: 0.88rem;
            padding: 4px 6px;
            height: 28px;
        }

        .table-compact .btn-sm {
            padding: 3px 8px;
            font-size: 0.82rem;
        }

        .table-compact th {
            white-space: nowrap;
            font-weight: 600;
        }

        .col-narrow {
            width: 95px !important;
            min-width: 95px !important;
        }

        .col-very-narrow {
            width: 70px !important;
            min-width: 70px !important;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
    <script>
        var counter = 1;
    </script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Edit Direct Purchase Invoice</span>
                                <?php
                        if($MenuPermission == true):?>
                                <?php else:?>
                                <span class="subHeadingLabelClass text-danger text-center" style="float: right">Permission
                                    Denied <span style='font-size:45px !important;'>&#128546;</span></span>
                                <?php endif;

                        ?>
                            </div>
                        </div>
                        <?php if($MenuPermission == true):?>
                        <div class="lineHeight">&nbsp;</div>


                        {{ Form::open(['url' => 'pad/updateDirectPurchaseInvoice?m=' . $m . '', 'id' => 'insertDirectPurchaseInvoice', 'class' => 'stop']) }}
                        @php
                            $pv_no = CommonHelper::uniqe_no_for_purcahseVoucher(date('y'), date('m'));
                        @endphp
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">PV NO.</label>
                                                <input readonly type="text" class="form-control requiredField"
                                                    placeholder="" name="pv_no" id="pv_no"
                                                    value="{{ $NewPurchaseVoucher->pv_no ?? strtoupper($pv_no) }}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">PV DATE.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField"
                                                    max="{{ date('Y-m-d') }}" name="pv_date" id="pv_date"
                                                    value="{{ $NewPurchaseVoucher->pv_date ?? '' }}" />
                                            </div> 
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mode of delivery</label>
                                                <input type="text" class="form-control" placeholder="Terms Of Delivery"
                                                    name="term_of_del" id="term_of_del" value="{{ $NewPurchaseVoucher->term_of_del ?? '' }}" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">PV Type</label>
                                                <select onchange="get_po(this.id)" name="po_type" id="po_type" class="form-control">
                                                    <option value="1" {{ (isset($NewPurchaseVoucher->purchase_type) && $NewPurchaseVoucher->purchase_type == 1) ? 'selected' : '' }}>Purchase Local</option>
                                                    <option value="2" {{ (isset($NewPurchaseVoucher->purchase_type) && $NewPurchaseVoucher->purchase_type == 2) ? 'selected' : '' }}>Internal Use</option>
                                                    <option value="3" {{ (isset($NewPurchaseVoucher->purchase_type) && $NewPurchaseVoucher->purchase_type == 3) ? 'selected' : '' }}>International</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="text" class="form-control" placeholder="Ref / Bill No" name="slip_no" id="slip_no"
                                                       value="{{ $NewPurchaseVoucher->slip_no ?? '' }}"/>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Bill Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control"  name="bill_date" id="bill_date" value="{{ $NewPurchaseVoucher->bill_date ?? '' }}" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="{{ $NewPurchaseVoucher->due_date ?? '' }}" readonly />
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Destination</label>
                                                <input style="text-transform: capitalize;" type="text" class="form-control"
                                                    placeholder="" name="destination" id="destination" value="{{ $NewPurchaseVoucher->destination ?? '' }}" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#"
                                                        onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');"
                                                        class="">Vendor</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_address()" name="supplier_id" id="supplier_id"
                                                    class="form-control requiredField select2">
                                                    <option value="">Select Vendor</option>
                                                    @foreach ($supplierList as $row1)
                                                        @php
                                                            $address = CommonHelper::get_supplier_address($row1->id);
                                                        @endphp
                                                        <option {{ $NewPurchaseVoucher->supplier == $row1->id ? 'selected' : '' }} value="<?php echo $row1->id . '@#' . $address . '@#' . $row1->ntn . '@#' . $row1->terms_of_payment; ?>"><?php echo ucwords($row1->name); ?></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#"
                                                        onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')"
                                                        class="">Currency</a></label>
                                                <span class="rflabelsteric"></span>
                                                <select onchange="get_rate()" name="curren" id="curren"
                                                    class="form-control select2 requiredField">
                                                    <option value=""> Select Currency</option>
                                                    @foreach(CommonHelper::get_all_currency_with_current_rate() as $row)
                                                        <option value="{{$row->id . ',' . $row->rate}}" {{ (isset($NewPurchaseVoucher->currency) && $NewPurchaseVoucher->currency == $row->id) ? 'selected' : '' }}>
                                                            {{$row->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Currency Rate</label>
                                                <span class="rflabelsteric"></span>
                                                <input class="form-control" type="text" name="currency_rate" id="currency_rate" value="1" />
                                                <input type="hidden" name="curren_rate" id="curren_rate" value="1" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mode/ Terms Of Payment <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                <input onkeyup="calculate_due_date()" type="number"
                                                    class="form-control" placeholder=""
                                                    name="model_terms_of_payment" id="model_terms_of_payment"
                                                    value="{{ $model_terms_of_payment }}" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Supplier's Address</label>
                                                <input style="text-transform: capitalize;" readonly type="text"
                                                    class="form-control" placeholder="" name="address" id="addresss"
                                                    value="" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Warehouse / Region <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                        <select onchange="get_address()" name="master_warehouse_id" id="master_warehouse_id"
                                                        class="form-control select2">
                                                        <option value="">Select</option>
                                                        @foreach (CommonHelper::get_all_warehouse() as $row1)
                                                       
                                                       <option {{ $NewPurchaseVoucher->warehouse == $row1->id ? 'selected' : '' }} value="{{ $row1->id }}">{{ $row1->name }}</option>     
                                                        @endforeach
                                                    </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Cost Center <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                <select class="form-control select2" name="sub_department_id" id="sub_department_id">
                                                    <option value="">Select Department</option>
                                                    @foreach($departmentsTwo as $key => $y)
                                                        <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                            <?php
                                                            $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` ='.$y->id.'');
                                                            ?>
                                                            @foreach($subdepartments as $key2 => $y2)
                                                                <option {{ $NewPurchaseVoucher->sub_department_id == $y2->id ? 'selected' : '' }} value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Supplier's NTN</label>
                                                <input readonly type="text" class="form-control" placeholder="Ntn"
                                                    name="ntn" id="ntn_id" value="" />
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>
                                    </div>
                                    <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Remarks</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="main_description" id="main_description" rows="4" cols="50"
                                            style="resize:none;font-size: 11px;" class="form-control requiredField">{{ $NewPurchaseVoucher->description }}</textarea>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-compact">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th colspan="14" class="text-center">Purchase Invoice Detail</th>
                                                        <th class="text-center">
                                                            <span class="badge badge-success" id="span">{{ $totalItemRows }}</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center nowrap" style="width: 9%; font-size:0.9rem;">Category</th>
                                                        <th class="text-center nowrap" style="width: 13%; font-size:0.9rem;">Item</th>
                                                        <th class="text-center nowrap col-very-narrow" style="font-size:0.9rem;">UoM<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Bags Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Qty (KG)<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Qty (lbs)<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Rate Cal. By<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Amount (PKR)<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.9rem;">Net Amount (PKR)<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.85rem;">DO No.</th>
                                                        <th class="text-center nowrap col-narrow" style="font-size:0.85rem;">Godown No.</th>
                                                        <th class="text-center nowrap" style="width: 11%; font-size:0.9rem;">Location<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center" style="width: 50px; font-size:0.9rem;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                    @foreach ($NewPurchaseVoucherData as $key => $DFil)
                                                        <tr id="RemoveRows{{ $key+1 }}" title="{{ $key+1 }}" class="AutoNo">
                                                            <td>
                                                                <select style="width: 100% !important;" onchange="get_sub_item('category_id{{ $key+1 }}')" name="category[]"
                                                                    id="category_id{{ $key+1 }}" class="form-control category select2 requiredField">
                                                                    <option value="">Select</option>
                                                                    @foreach (CommonHelper::get_all_category() as $category)
                                                                        <option value="{{ $category->id }}" {{ ($DFil->category_id == $category->id) ? 'selected' : '' }}>
                                                                            {{ $category->main_ic }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select style="width: 100% !important;" onchange="get_item_name({{ $key+1 }})" name="item_id[]" id="item_id{{ $key+1 }}"
                                                                    class="form-control requiredField select2 item_id_select" data-selected="{{ $DFil->sub_item }}">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id{{ $key+1 }}" value="{{ $DFil->uom }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control requiredField BagsQty" name="bags_qty[]" id="bags_qty{{ $key+1 }}" value="{{ $DFil->bag_qty ?? 1 }}" min="1"
                                                                    oninput="bag_qq({{ $key+1 }})">
                                                            </td>
                                                            <td>
                                                                <input type="text" onchange="claculation('{{ $key+1 }}')" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty{{ $key+1 }}"
                                                                    placeholder="ACTUAL QTY" min="1" value="{{ $DFil->qty ?? '' }}" readonly>
                                                                <input type="hidden" class="PackQty" name="pack_qty[]" id="pack_qty">
                                                            </td>
                                                            <td>
                                                                <input class="form-control requiredField" type="number" id="qty_lbs{{ $key+1 }}" name="qty_lbs[]" step="any" value="{{ $DFil->lbs_qty ?? '' }}" readonly />
                                                            </td>
                                                            <td>
                                                                <select required class="form-control select2" name="rate_cal_by[]" id="rate_cal_by_{{ $key+1 }}" onchange="calculateLineAmount(this)">
                                                                    <option value="1" {{ ($DFil->rate_cal_by == 1) ? 'selected' : '' }}>By BAGS</option>
                                                                    <option value="2" {{ ($DFil->rate_cal_by == 2) ? 'selected' : '' }}>By KGS</option>
                                                                    <option value="3" {{ ($DFil->rate_cal_by == 3) ? 'selected' : '' }}>By LBS</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" onkeyup="claculation('{{ $key+1 }}')" class="form-control requiredField ActualRate" name="rate[]" id="rate{{ $key+1 }}" placeholder="RATE" min="1" value="{{ $DFil->rate ?? '' }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control number_format" name="amount[]" id="amount{{ $key+1 }}" placeholder="AMOUNT" min="1" value="{{ $DFil->amount ?? '' }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control actual_amount number_format" name="actual_amount[]" id="actual_amount{{ $key+1 }}" placeholder="AMOUNT" min="1" value="{{ $DFil->amount ?? '' }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control net_amount_dis number_format" name="after_dis_amount[]" id="after_dis_amount{{ $key+1 }}" placeholder="NET AMOUNT" min="1" value="{{ $DFil->net_amount ?? '' }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="do_no[]" id="do_no_{{ $key+1 }}" placeholder="DO No." value="{{ $DFil->do_no ?? '' }}" />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="godown_no[]" id="godown_no_{{ $key+1 }}" placeholder="Godown No." value="{{ $DFil->godown_no ?? '' }}" />
                                                            </td>
                                                            <td>
                                                                <select required class="form-control select2" name="warehouse_id[]" id="warehouse_{{ $key+1 }}">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                        <option value="{{ $row->id }}" {{ ($DFil->warehouse_id == $row->id) ? 'selected' : '' }}>{{ $row->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="background-color: #ccc" class="text-center">
                                                                @if($key == 0)
                                                                    <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="+" />
                                                                @else
                                                                    <button type="button" class="btn btn-sm btn-danger" id="BtnRemove{{ $key+1 }}" onclick="RemoveSection({{ $key+1 }})"> - </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                                <tbody>
                                                    <tr style="font-size:large;font-weight: bold">
                                                        <td class="text-center" colspan="7">Total</td>
                                                        <td class="text-right" colspan="1"><input readonly class="form-control number_format" type="text" name="pkr_net" id="pkr_net" /> </td>
                                                        <td class="text-right" colspan="1"><input readonly class="form-control number_format" type="text" name="actual_net" id="actual_net" /> </td>
                                                        <td class="text-right" colspan="1"><input readonly class="form-control number_format" type="text" id="net" name="net" /></td>
                                                        <td colspan="5"></td>
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
                                                        <select onchange="sales_tax(this.id)"
                                                            class="form-control select2" id="sales_taxx"
                                                            name="sales_taxx">
                                                            <option value="0">Select</option>
                                                            @foreach (ReuseableCode::get_all_sales_tax() as $row)
                                                                <option  value="{{ $row->percent.'@'.$row->acc_id }}" {{($row->percent == "17.000")? 'selected' : ''}}>
                                                                    {{ $row->percent }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-right" colspan="3">
                                                        <input onkeyup="" type="text"
                                                            class="form-control" name="sales_amount_td"
                                                            id="sales_amount_td" value="{{ $NewPurchaseVoucher->sales_tax_amount }}" />
                                                    </td>
                                                    <input type="hidden" name="sales_amount" id="sales_tax_amount" value="{{ $NewPurchaseVoucher->sales_tax_amount }}" />
                                                </tr>


                                            </tbody>

                                            <tbody>
                                                <tr style="font-size:large;font-weight: bold">
                                                    <td class="text-center" colspan="3">Total Amount After Tax</td>
                                                    <td id="" class="text-right" colspan="3"><input readonly
                                                            class="form-control" type="text" id="net_after_tax" />
                                                    </td>
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
                    <?php echo Form::close(); ?>
                    <?php endif;?>
                </div>

            </div>
        </div>
    </div>
    <script>
        function itemChange(id) {
            $('#uom_id'+id).val($('#sub_'+id).find(':selected').data("uom"))
        }
        var Counter = {{ $totalItemRows }};

        function AddMoreDetails() {
            Counter++;
            var category = 'category_id' + Counter;

            $('#AppnedHtml').append(
                '<tr id="RemoveRows' + Counter + '" class="AutoNo">' +
                '<td>' +
                '<select style="width: 100% !important;" onchange="get_sub_item(`' + category + '`)" name="category[]" id="category_id' + Counter + '"  class="form-control category select2 requiredField">' +
                '<option value="">Select</option>' +
                '@foreach (CommonHelper::get_all_category() as $category):' +
                '<option value="{{ $category->id }}"> {{ $category->main_ic }} </option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select style="width: 100% !important;" onchange="get_item_name(' + Counter + ')" name="item_id[]" id="item_id' + Counter + '" class="form-control requiredField select2 item_id_select" data-selected="">' +
                '<option value="">Select</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id' + Counter + '">' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control requiredField BagsQty" name="bags_qty[]" id="bags_qty' + Counter + '" value="1" min="1" oninput="bag_qq(' + Counter + ')">' +
                '</td>' +
                '<td>' +
                '<input type="text" onchange="claculation(' + Counter + ')" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty' + Counter + '" placeholder="ACTUAL QTY" readonly>' +
                '<input type="hidden" class="PackQty" name="pack_qty[]" id="pack_qty">' +
                '</td>' +
                '<td>' +
                '<input class="form-control requiredField" type="number" id="qty_lbs' + Counter + '" name="qty_lbs[]" step="any" readonly />' +
                '</td>' +
                '<td>' +
                '<select required class="form-control select2" name="rate_cal_by[]" id="rate_cal_by_' + Counter + '" onchange="calculateLineAmount(this)">' +
                '<option value="1">By BAGS</option>' +
                '<option value="2">By KGS</option>' +
                '<option value="3">By LBS</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input type="text" onkeyup="claculation(' + Counter + ')" class="form-control requiredField ActualRate" name="rate[]" id="rate' + Counter + '" placeholder="RATE" min="1" value="">' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control number_format" name="amount[]" id="amount' + Counter + '" placeholder="AMOUNT" min="1" value="" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control actual_amount number_format" name="actual_amount[]" id="actual_amount' + Counter + '" placeholder="AMOUNT" min="1" value="" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control net_amount_dis number_format" name="after_dis_amount[]" id="after_dis_amount' + Counter + '" placeholder="NET AMOUNT" min="1" value="" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="do_no[]" id="do_no_' + Counter + '" placeholder="DO No." />' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="godown_no[]" id="godown_no_' + Counter + '" placeholder="Godown No." />' +
                '</td>' +
                '<td>' +
                '<select required class="form-control select2" name="warehouse_id[]" id="warehouse_' + Counter + '">' +
                '<option value="">Select</option>' +
                '@foreach(CommonHelper::get_all_warehouse() as $row)' +
                '<option value="{{ $row->id }}">{{ $row->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td style="background-color: #ccc" class="text-center">' +
                '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove' + Counter + '" onclick="RemoveSection(' + Counter + ')"> - </button>' +
                '</td>' +
                '</tr>'
            );

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            $('.select2').select2();
            $('.number_format').number(true, 2);
        }

        function RemoveSection(Row) {
            $('#RemoveRows' + Row).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
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


        $('.sam_jass').bind("enterKey", function(e) {


            $('#items').modal('show');


        });
        $('.sam_jass').keyup(function(e) {
            if (e.keyCode == 13) {
                selected_id = this.id;
                $(this).trigger("enterKey");


            }

        });


        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        function tax_by_amount(id) {


            var tax_percentage = $('#sales_taxx').val();



            if (tax_percentage == 0) {

                $('#' + id).val(0);
            } else {
                var tax_amount = parseFloat($('#' + id).val());

                // highlight end

                if (isNaN(tax_amount) == true) {
                    tax_amount = 0;
                }
                var count = 1;
                var amount = 0;
                $('.net_amount_dis').each(function() {


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
            $('.net_amount_dis').each(function(i, obj) {

                amount += +$('#' + obj.id).val();


            });
            amount = parseFloat(amount);
            $('#net').val(amount);
            var sales_tax = parseFloat($('#sales_amount_td').val());


            var net = (amount + sales_tax).toFixed(2);
            $('#net_after_tax').val(net);
            $('#d_t_amount_1').val(net);
            toWords(1);

        }



        function view_history(id) {

            var v = $('#sub_' + id).val();


            if ($('#view_history' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
                } else {
                    alert('Select Item');
                }

            }





        }





        $(document).ready(function() {
            amount_calculation(1);
            // Fill currency rate and supplier address/ntn on load (edit screen)
            if ($('#curren').val()) {
                get_rate();
            }
            if ($('#supplier_id').val()) {
                get_address();
            }
            init_edit_rows();
            for (i = 1; i <= counter; i++) {
                $('#amount_' + i).number(true, 2);
                //   $('#rate_'+i).number(true,2);
                $('#purchase_approve_qty_' + i).number(true, 2);


                $('#after_dis_amountt' + i).number(true, 2);
                $('#rate_' + i).number(true, 2);
            }

            $('#d_t_amount_1').number(true, 2);
            $('#sales_amount_td').number(true, 2);

            $(".btn-success").click(function(e) {
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
                        $('.ActualQty').each(function() {
                            vala = parseFloat($(this).val());
                            if (vala == 0) {
                                alert('Please Enter Correct Actual Qty....!');
                                $(this).css('border-color', 'red');
                                flag = true;
                                return false;
                            } else {
                                $(this).css('border-color', '#ccc');
                            }
                        });

                        $('.ActualRate').each(function() {
                            vala = parseFloat($(this).val());
                            if (vala == 0) {
                                alert('Please Enter Correct Rate Qty....!');
                                $(this).css('border-color', 'red');
                                flag = true;
                                return false;
                            } else {
                                $(this).css('border-color', '#ccc');
                            }
                        });
                        if (flag == true) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }

            });


            $(document).keypress("m", function(e) {
                if (e.ctrlKey)
                    AddMoreDetails();
            });

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

        $(document).ready(function() {
            //            toWords(1);
        });


        function claculation(number) {
            var qty = $('#actual_qty' + number).val();
            var rate = $('#rate' + number).val();

            var total = parseFloat(qty * rate).toFixed(2);

            $('#amount' + number).val(total);

            var amount = 0;
            count = 1;
            $('.net_amount_dis').each(function(i, obj) {

                amount += +$('#' + obj.id).val();

                count++;
            });
            amount = parseFloat(amount);


            sales_tax('sales_taxx');
            discount_percent('discount_percent' + number);
            net_amount();
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
          
            $('.net_amount_dis').each(function() {

                amount += +$(this).val();

            });
            $('#net').val(amount);
        
            var sales_tax = parseFloat($('#sales_amount_td').val());
            var net = (amount + sales_tax).toFixed(2);
          
            $('#net_after_tax').val(net);
            console.log(net);
        

        }
        // function sales_tax(id) {

        //     var sales_tax_per_value = $('#' + id).val();

        //     if (sales_tax_per_value != 0) {
        //         var sales_tax_per = $('#' + id + ' :selected').text();
        //         sales_tax_per = sales_tax_per.split('(');
        //         sales_tax_per = sales_tax_per[1];
        //         sales_tax_per = sales_tax_per.replace('%)', '');

        //     } else {
        //         sales_tax_per = 0;
        //     }

        //     count = 1;
        //     var amount = 0;
        //     $('.net_amount_dis').each(function() {


        //         amount += +$(this).val();
        //         count++;
        //     });


        //     var x = parseFloat(sales_tax_per * amount);
        //     var s_tax_amount = parseFloat(x / 100).toFixed(2);

        //     $('#sales_tax_amount').val(s_tax_amount);
        //     $('#sales_amount_td').val(s_tax_amount);

        //     var amount = 0;
        //     count = 1;
        //     $('.net_amount_dis').each(function() {


        //         amount += +$('#after_dis_amountt_' + count).val();
        //         count++;
        //     });
        //     amount = parseFloat(amount);
        //     s_tax_amount = parseFloat(s_tax_amount);
        //     var total_amount = (amount + s_tax_amount).toFixed(2);
        //     $('.td_amount').text(total_amount);
        //     $('#d_t_amount_1').val(total_amount);
        //     net_amount();
        //     //   toWords(1);



        // }


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
            $('#currency_rate').val(currency_id[1]);
            $('#curren_rate').val(currency_id[1]);
        }

        // ===== Create-form compatible helpers (needed for UOM + qty/amount calc) =====
        function get_item_name(index) {
            var item = $('#item_id' + index).val();
            if (!item) return;
            var uom = item.split('@');
            $('#uom_id' + index).val(uom[1] || '');
            $('#actual_qty' + index).val(uom[3] || 0);
            $('#qty_lbs' + index).val((parseFloat(uom[3] || 0) * 2.2).toFixed(2));
            $('#pack_qty').val(uom[3] || 0);
            if (!$('#bags_qty' + index).val()) {
                $('#bags_qty' + index).val(1);
            }
            bag_qq(index);
        }

        function bag_qq(counter) {
            var bags_qty = parseFloat($('#bags_qty' + counter).val()) || 1;
            var pack_qty = parseFloat($('#pack_qty').val()) || 0;
            var total_qty = (bags_qty * pack_qty).toFixed(2);
            $('#actual_qty' + counter).val(total_qty);
            $('#qty_lbs' + counter).val((total_qty * 2.2).toFixed(2));
            claculation(counter);
        }

        function claculation(number) {
            var rate_cal_by = $('#rate_cal_by_' + number).val() || "0";
            var bags_qty = parseFloat($('#bags_qty' + number).val()) || 0;
            var qty_kg = parseFloat($('#actual_qty' + number).val()) || 0;
            var qty_lbs = parseFloat($('#qty_lbs' + number).val()) || 0;
            var rate = parseFloat($('#rate' + number).val()) || 0;

            var currency = $('#currency_rate').val() || 1;
            if (currency === '') currency = 1;
            currency = parseFloat(currency);

            var multiplier = 0;
            if (rate_cal_by === "1") multiplier = bags_qty;
            else if (rate_cal_by === "2") multiplier = qty_kg;
            else if (rate_cal_by === "3") multiplier = qty_lbs;

            var total = (multiplier * rate * currency).toFixed(2);
            $('#amount' + number).val(total);
            $('#actual_amount' + number).val(total);
            $('#after_dis_amount' + number).val(total);

            net_amount();
            sales_tax('sales_taxx');
        }

        function calculateLineAmount(element) {
            var rowCounter = element.id.replace('rate_cal_by_', '');
            claculation(rowCounter);
        }

        // Load existing row item dropdowns and select saved items (edit screen)
        function init_edit_rows() {
            $('.item_id_select').each(function () {
                var $sel = $(this);
                var row = $sel.attr('id').replace('item_id', '');
                var categoryId = $('#category_id' + row).val();
                var selectedSubItem = $sel.data('selected'); // numeric id saved in db

                if (categoryId) {
                    get_sub_item('category_id' + row);
                }

                // wait until get_sub_item ajax fills options, then select the saved item
                var tries = 0;
                var t = setInterval(function () {
                    tries++;
                    var $itemSel = $('#item_id' + row);
                    if ($itemSel.find('option').length > 1) {
                        // options are like: "ID@UOM@NAME@PACK"
                        var matched = false;
                        $itemSel.find('option').each(function () {
                            var v = $(this).val();
                            if (!v) return;
                            if ((v + '').startsWith(selectedSubItem + '@') || v == selectedSubItem) {
                                $itemSel.val(v).trigger('change');
                                matched = true;
                                return false;
                            }
                        });
                        if (!matched) {
                            // keep as-is
                        }
                        clearInterval(t);
                    }
                    if (tries > 20) clearInterval(t);
                }, 150);
            });
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
            } else {
                $('#net_amounttd_' + number).val(amount_after_discount);
                $('#after_dis_amount' + number).val(amount_after_discount);
            }
            $('#cost_center_dept_amount' + number).text(amount_after_discount);
            $('#cost_center_dept_hidden_amount' + number).val(amount_after_discount);
            sales_tax('sales_taxx');
            net_amount();
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
            net_amount();
        }

        function get_detail(id, number) {
            var item = $('#' + id).val();
            $.ajax({
                url: '{{ url('/pdc/get_data') }}',
                data: {
                    item: item
                },
                type: 'GET',
                success: function(response) {
                    var data = response.split(',');
                    $('#uom_id' + number).val(data[0]);
                }
            })
        }
        $(".remove").each(function() {
            $(this).html($(this).html().replace(/,/g, ''));
        });

        function calculate_due_date() {
            var date = new Date($("#pv_date").val());
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
    </script>
    <script type="text/javascript">
        $('.select2').select2();

        function amount_calculation(number) {
            var amount = $('#amount' + number).val();
               // var rate = $('#rate' + number).val();

               
            // $('#amount' + number).val(total);
            var total = parseFloat(amount).toFixed(2);

            var amount = 0;
            count = 1;
            $('.net_amount_dis').each(function(i, obj) {

                amount += +$('#' + obj.id).val();

                count++;
            });
            amount = parseFloat(amount);



            discount_percent('discount_percent' + number);
            net_amount();
            sales_tax('sales_taxx');
            //  toWords(1);
        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
