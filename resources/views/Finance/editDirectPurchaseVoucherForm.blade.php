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
                                                <label class="sf-label">Mode/ Terms Of Payment <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                <input onkeyup="calculate_due_date()" type="number"
                                                    class="form-control" placeholder=""
                                                    name="model_terms_of_payment" id="model_terms_of_payment"
                                                    value="{{ $model_terms_of_payment }}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Warehouse / Region <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                        <select onchange="get_address()" name="warehouse_id" id="warehouse_id"
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
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th colspan="6" class="text-center">Purchase Order Detail</th>
                                                        <th colspan="2" class="text-center">
                                                            <input type="button" class="btn btn-sm btn-primary"
                                                                onclick="AddMoreDetails()" value="Add More Rows" />
                                                        </th>
                                                        <th class="text-center">
                                                            <span class="badge badge-success" id="span">1</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 35%;">Item</th>
                                                        <th class="text-center hide">Uom<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center hide"> QTY<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center hide">Rate<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center hide">Discount %<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center hide">Discount Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center hide">Net Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center" colspan="7">Delete<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                    @foreach ($NewPurchaseVoucherData as $key => $DFil)
                                                        
                                                    
                                                    <tr id="RemoveRows{{ $key+1 }}" title="{{ $key+1 }}" class="AutoNo">
                                                        <td>
                                                            <select name="item_id[]" id="sub_1" onchange="itemChange({{ $key+1 }})"
                                                                class="form-control select2">                                                                
                                                                
                                                                @foreach(CommonHelper::get_all_account_operat() as $key1 => $y)
                                                                    <option @if($DFil->sub_item == $y->id) selected @endif value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td   class="hide">
                                                            <input readonly type="text" class="form-control"
                                                                name="uom_id[]" id="uom_id{{ $key+1 }}" value="{{ $DFil->uom }}">
                                                        </td>
                                                        <td  class="hide">
                                                            <input type="text" onkeyup="claculation({{ $key+1 }})" onblur="claculation({{ $key+1 }})"
                                                                class="form-control  ActualQty"
                                                                name="actual_qty[]" id="actual_qty{{ $key+1 }}"
                                                                placeholder="ACTUAL QTY" min="1" value="{{ $DFil->qty }}">
                                                        </td>
                                                        <td   class="hide">
                                                            <input type="text" onkeyup="claculation({{ $key+1 }})" onblur="claculation({{ $key+1 }})"
                                                                class="form-control  ActualRate"
                                                                name="rate[]" id="rate{{ $key+1 }}" placeholder="RATE"
                                                                min="1" value="{{ $DFil->rate }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control requiredField" name="amount[]"
                                                                id="amount{{ $key+1 }}" placeholder="AMOUNT" min="1"
                                                                value="{{ $DFil->amount }}" onkeyup="amount_calculation({{ $key+1 }})" >
                                                        </td>
                                                        <td   class="hide">
                                                            @php
                                                                if($DFil->discount_amount != 0){
                                                                    $total_value = (int)$DFil->discount_amount + (int)$DFil->net_amount;
                                                                    $discount_percent = (int)$DFil->discount_amount / $total_value;
                                                                }else {
                                                                    $discount_percent = 0;
                                                                }
                                                                
                                                            @endphp
                                                            <input type="text" onkeyup="discount_percent(this.id)"
                                                                class="form-control"
                                                                name="discount_percent[]" id="discount_percent{{ $key+1 }}"
                                                                placeholder="DISCOUNT" min="1" value="{{ $discount_percent * 100 }}">
                                                        </td>
                                                        <td   class="hide">
                                                            <input type="text" onkeyup="discount_amount(this.id)"
                                                                class="form-control"
                                                                name="discount_amount[]" id="discount_amount{{ $key+1 }}"
                                                                placeholder="DISCOUNT" min="1" value="{{ $DFil->discount_amount }}">
                                                        </td>
                                                        <td   class="hide">
                                                            <input type="text" class="form-control requiredField net_amount_dis"
                                                                name="after_dis_amount[]" id="after_dis_amount{{ $key+1 }}"
                                                                placeholder="NET AMOUNT" min="1" value="{{ $DFil->net_amount }}"
                                                                readonly>
                                                        </td>
                                                        @if($key > 0)

                                                            <td style="background-color: #ccc" colspan="7">
                                                                <!-- <input onclick="view_history({{ $key+1 }})" type="checkbox"
                                                                    id="view_history{{ $key+1 }}"> -->
                                                                <button type="button" class="btn btn-sm btn-danger" id="BtnRemove{{ $key+1 }}" onclick="RemoveSection({{ $key+1 }})"> - </button>
                                                            </td>
                                                        @endif    
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                                <tbody>
                                                    <tr
                                                        style="font-size:large;font-weight: bold">
                                                        <td class="text-center" colspan="">Total</td>
                                                        <td id="" class="text-right" colspan=""><input
                                                                readonly class="form-control" type="text"
                                                                id="net" /> </td>
                                                        <td colspan="7"></td>
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
            // '<input type="text" class="form-control sam_jass" name="sub_ic_des[]" id="item_' + Counter +
            // '" placeholder="ITEM">' +
            // '<input type="hidden" class="requiredField" name="item_id[]" id="sub_' + Counter + '">' +
            $('#AppnedHtml').append(`
                    <tr id="RemoveRows${Counter}" class="AutoNo">
                        <td class="AutoCounter" title="${AutoCount}">
                            <select name="item_id[]" id="sub_${Counter}" onchange="itemChange(${Counter})" class="form-control select2">
                                <option value="">Select</option>
                                <?php foreach(CommonHelper::get_all_account_operat() as $Fil){?>
                                    <option value=<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?>
                                </select>
                            </select>
                        </td>
                        <td class="hide">
                            <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}" >
                        </td>
                        <td class="hide">
                            <input type="text" onkeyup="claculation(${Counter})" onblur="claculation(${Counter})"  class="form-control ActualQty" name="actual_qty[]" id="actual_qty${Counter}" placeholder="ACTUAL QTY">
                        </td>
                        <td class="hide">
                            <input type="text" onkeyup="claculation(${Counter})" onblur="claculation(${Counter})" class="form-control ActualRate" name="rate[]" id="rate${Counter}" placeholder="RATE">
                        </td>
                        <td>
                            <input type="text" class="form-control requiredField"  onkeyup="amount_calculation(${Counter})" name="amount[]" id="amount${Counter}" placeholder="AMOUNT">
                        </td>
                        <td class="hide">
                            <input type="text" onkeyup="discount_percent(this.id)" class="form-control " value="0" name="discount_percent[]" id="discount_percent${Counter}" placeholder="DISCOUNT">
                        </td>
                        <td class="hide">
                            <input type="text" onkeyup="discount_amount(this.id)" class="form-control " value="0" name="discount_amount[]" id="discount_amount${Counter}" placeholder="DISCOUNT">
                        </td>
                        <td class="hide">
                            <input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" placeholder="NET AMOUNT">
                        </td>
                        <td class="text-center" colspan="7">
                            <button type="button" class="btn btn-sm btn-danger" id="BtnRemove${Counter}" onclick="RemoveSection(${Counter})"> - </button>
                            </td>
                    </tr>
            `);

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            $('.select2').select2();

            var AutoCount = 1;;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).prop('title', AutoCount);

            });
            $('.sam_jass').bind("enterKey", function(e) {


                $('#items').modal('show');


            });
            $('.sam_jass').keyup(function(e) {
                if (e.keyCode == 13) {
                    selected_id = this.id;
                    $(this).trigger("enterKey");


                }

            });


        }

        function RemoveSection(Row) {
            //            alert(Row);
            $('#RemoveRows' + Row).remove();
            //   $(".AutoCounter").html('');
            var AutoCount = 1;
            var AutoCount = 1;;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).prop('title', AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            amount_calculation(1);
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
