<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), 1);
$sub_department_input = '';
foreach ($companydepartments as $key => $y) {
    $sub_department_input .= '<optgroup label="' . $y->department_name . '" value="' . $y->id . '">';
    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` =' . $y->id . '');
    foreach ($subdepartments as $key2 => $y2) {
        $sub_department_input .= '<option value="' . $y2->id . '">' . $y2->sub_department_name . '</option>';
    }
    $sub_department_input .= '</optgroup>';

}


?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <style>
        .addAmountRow {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: baseline;
        }

        .addAmountRowInput {
            width: 75%
        }

        .addAmountRowRemoveBtn {
            height: 6vh;
            padding: 6px 23px !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class='headquidd'>
                                <span class="subHeadingLabelClass">Create Bank Payment Voucher Form</span>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => '/insertBankPayment?m=' . $m . '', 'id' => 'bankPaymentVoucherForm'));?>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="pvsSection[]" class="form-control requiredField"
                                                id="pvsSection" value="1" />
                                        </div>
                                    </div>
                         
                                    <!-- <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="">Advance Payment <input type="checkbox" name="advance_payment"
                                                    id="advance_payment" value="1"></label>

                                        </div>
                                    </div> -->
                                    <div class="row">

                                        <input type="hidden" name="type" id="type" value="1" />
                                        <input checked type="hidden" class="" value="1" name="payment_mod" />

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PV No</label>
                                            <span
                                                style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"
                                                placeholder="Slip No" name="" id="" value="{{strtoupper($pv_no)}}" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PV Date.</label>
                                            <span
                                                style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input autofocus onblur="" onchange="" type="date"
                                                class="form-control requiredField" max="<?php echo date('Y-m-d') ?>"
                                                name="pv_date_1" id="pv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                            <label class="sf-label">PV Day.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control" name="pv_day" id="pv_day" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Ref / Bill No.</label>
                                            <span
                                                style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input type="text" class="form-control" placeholder="Slip No" name="slip_no_1"
                                                id="slip_no_1" value="-" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Bill Date.</label>

                                            <input type="date" class="form-control" name="bill_date" id="bill_date"
                                                value="" />
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Cheque No.</label>
                                            <span
                                                style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" placeholder="Cheque No"
                                                name="cheque_no_1" id="cheque_no_1" value="" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Cheque Date.</label>
                                            <span
                                                style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" name="cheque_date_1"
                                                id="cheque_date_1" value="{{date('Y-m-d')}}" />
                                        </div>

                                        <div class="col-lg-6 col-md-3 col-sm-6 col-xs-12 hide">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div >
                                                        <table class="userlittab table sf-table-list">
                                                            <thead>
                                                                <tr>
                                                                    <th>Debit Account.</th>
                                                                    <th>Debit Amount </th>
                                                                    <th>Action </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="addAmountTr" id="addAmountTr">
                                                               
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="lineHeight">&nbsp;</div>

                                    <!-- <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label"><input type="checkbox" id="TaxApplicable" value="1"
                                                    onclick="ShowHide();"> Tax Applicable </label>
                                        </div>
                                    </div> -->
                                    <div class="row CheckBoxArea" style="display: none;">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <label class="sf-label"><input type="checkbox" id="IncomeTaxWithholding"
                                                    onclick="IncomeTax()"> Income Tax Withholding </label>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <label class="sf-label"><input type="checkbox" id="FbrSalesTaxWithholding"
                                                    onclick="FbrSalesTax()"> FBR Sales Tax Withholding </label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <label class="sf-label"><input type="checkbox" id="SrbSindhRevenueBoard"
                                                    onclick="SrbSindh()"> SRB (Sindh Revenue Board)</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <label class="sf-label"><input type="checkbox" id="PunjabSalesTaxWithholding"
                                                    onclick="PunjabSalesTax()"> Punjab Sales Tax Withholding</label>
                                        </div>
                                    </div>
                                    <span id="IncomeTaxWithholdingData"></span>
                                    <span id="FbrSalesTaxWithholdingData"></span>
                                    <span id="SrbSindhRevenueBoardData"></span>
                                    <span id="PunjabSalesTaxWithholdingData"></span>


                                    <div class="lineHeight">&nbsp;</div>
                                    <div class='jhed headquid'>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span class="subHeadingLabelClass">Bank Payment Voucher Detail</span>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                <input type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()"
                                                    value="Add More PV's Rows" />
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div >
                                                <table id="buildyourform"
                                                    class="userlittab table table-bordered sf-table-list">
                                                    <thead>

                                                        <tr>
                                                            <th class="text-center hidden-print"><a href="#"
                                                                    onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')"
                                                                    class="">Credit Account</a>
                                                            </th>


                                                            <th class="text-center hide" style="width:150px;">Cost Center
                                                            </th>
                                                            <th class="text-center hide" style="width:350px;">Description
                                                                <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center" style="width:150px;">Credit <span
                                                                    class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center" style="width:150px;">Debit</th>
                                                            <th class="text-center" style="width:150px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                                        <?php for($j = 1 ; $j <= 2 ; $j++){?>
                                                <input type="hidden" name="rvsDataSection_1[]" class="form-control" id="rvsDataSection_1" value="<?php echo $j?>" />
                                                <tr class="AutoNo">
                                                    <td>
                                                        <select class="form-control requiredField select2" name="account_id[]" id="account_id{{$j}}">
                                                            <option value="">Select Account</option>
                                                            @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                                                                <option value="{{ $y->id.',0'}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="hide">
                                                        <?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>
                                                        <select class="form-control select2" name="paid_to[]" id="paid_to{{$j}}">
                                                            <option value='0,0'>Select Paid To</option>
                                                            <?php foreach($MultiData['Emp'] as $EmpFil):?>
                                                            <option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
                                                            <?php endforeach;?>
                                                            <?php foreach($MultiData['Supp'] as $SuppFil):?>
                                                            <option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>
                                                            <?php endforeach;?>
                                                            <?php foreach($MultiData['Client'] as $ClientFil):?>
                                                            <option value='<?php echo $ClientFil->id.","."3";?>'><?php echo $ClientFil->client_name?></option>
                                                            <?php endforeach;?>
                                                            <?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
                                                            <option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>
                                                            <?php endforeach;?>

                                                        </select>
                                                    </td>
                                                    <td class="hide">
                                                        <textarea class="form-control" name="desc[]" id="desc_1_{{$j}}"/></textarea>
                                                    </td>
                                                    <td>
                                                        <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField number_format" maxlength="15" min="0" type="text" name="d_amount[]" id="d_amount_1_{{$j}}" onkeyup="sum('1')" value="" required="required"/>
                                                    </td>
                                                    <td>
                                                        <input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField number_format" maxlength="15" min="0" type="text" name="c_amount[]" id="c_amount_1_{{$j}}" onkeyup="sum('1')" value="" required="required"/>
                                                    </td>
                                                    <td class="text-center">---</td>
                                                </tr>
                                                <?php }?>
                                                    </tbody>
                                                </table>
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td style="width:150px;">
                                                                <input type="number" readonly="readonly" id="d_t_amount_1"
                                                                    maxlength="15" min="0" name="d_t_amount_1"
                                                                    class="form-control requiredField text-right"
                                                                    value="" />
                                                            </td>
                                                            <td style="width:150px;">
                                                                <input type="number" readonly="readonly" id="c_t_amount_1"
                                                                    maxlength="15" min="0" name="c_t_amount_1"
                                                                    class="form-control requiredField text-right"
                                                                    value="">
                                                            </td>
                                                            <td class="diff" style="width:150px;font-size: 20px;">
                                                                <input readonly style="color: blue;font-weight: 600"
                                                                    class="form-control" type="text" id="diff" value="" />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span
                                                    style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                <textarea name="description_1" id="description_1" style="resize:none;"
                                                    class="form-control requiredField"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pvsSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <div class="headquid">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                </div>


                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {




            $('.select2').select2();
            $('.number_format').number(true, 2);
        });
    </script>

    <script>
        var x = 2;
        var x2 = 1;
        function AddMorePvs() {

            $('#addMorePvsDetailRows_1').append(`

                <tr class='AutoNo' id='tr${x}' >
                        <td>
                            <select class='form-control requiredField select2' onchange='Datavalidate(this)' name='account_id[]' id='account_id"+x+"'>
                                <option value=''>Select Account</option>
                                @foreach(CommonHelper::get_all_account_operat() as $Fil)
                                    <option value=<?php    echo $Fil->id . ',0' . ',' . $Fil->code?>'><?php    echo $Fil->code . '--' . $Fil->name;?></option>
                                @endforeach
                            </select>
                        </td>
                        <td class='hide'>
                            <select class='form-control select2 sub_department_id' name='sub_department_id[]' id=''><option value=''>Select Department</option>
                                '{!! $sub_department_input !!}'+
                            </select>
                        </td>
                        <td class='hide'>
                            <textarea class="form-control" name="desc[]" id="desc_1_${x}" /> </textarea>'+
                        </td>

                        <td>
                        <input  placeholder="Credit" class="form-control c_amount_1_${x} d_amount_'+x2+' requiredField" onfocus="" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_${x}" onkeyup="sum('1')" value="" required="required"/>
                        </td>               
                        <td>
                            <input
                                placeholder="Debit"
                                class="form-control d_amount_1 requiredField"
                                maxlength="15" min="0" type="text" name="d_amount[]" id="d_amount_1_${x}" onkeyup="sum('1')" value=""
                                required="required" />
                        </td>
                        <td class='text-center'> 
                            <input type='button' onclick='RemoveRow("${x}")' value='Remove' class='btn btn-sm btn-danger'> 
                        </td>
                </tr>


                `);

            $('.select2').select2();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            x++;


            // $('.d_amount_1_3').number(true,2);
        }

        function ShowHide() {
            if ($('#TaxApplicable').is(':checked')) {
                $('.CheckBoxArea').css('display', 'block');
            }
            else {
                $('.CheckBoxArea').css('display', 'none');

                $("#IncomeTaxWithholding").prop("checked", false);
                $("#IncomeTaxWithholdingData").html("");

                $("#FbrSalesTaxWithholding").prop("checked", false);
                $("#FbrSalesTaxWithholdingData").html("");

                $("#SrbSindhRevenueBoard").prop("checked", false);
                $("#SrbSindhRevenueBoardData").html("");

                $("#PunjabSalesTaxWithholding").prop("checked", false);
                $("#PunjabSalesTaxWithholdingData").html("");
            }
        }

        function RemoveRow(x) {
            $('#tr' + x).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            sum('1');
        }
    </script>
    <script !src="">

        function PunjabSalesTax() {
            if ($('#PunjabSalesTaxWithholding').is(':checked')) {
                //$('.FbrSalesTaxWithholding').css('display','block');
                $('#PunjabSalesTaxWithholdingData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fdc/showPunjabSalesTaxWithholding',
                    type: "GET",
                    data: {},
                    success: function (data) {
                        $('#PunjabSalesTaxWithholdingData').html(data);

                    }
                });
            }
            else { $('#PunjabSalesTaxWithholdingData').html(''); }
        }

        function SrbSindh() {
            if ($('#SrbSindhRevenueBoard').is(':checked')) {
                //$('.FbrSalesTaxWithholding').css('display','block');
                $('#SrbSindhRevenueBoardData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fdc/showSrbSindhRevenue',
                    type: "GET",
                    data: {},
                    success: function (data) {
                        $('#SrbSindhRevenueBoardData').html(data);

                    }
                });
            }
            else { $('#SrbSindhRevenueBoardData').html(''); }
        }

        function IncomeTax() {
            if ($('#IncomeTaxWithholding').is(':checked')) {
                $('#IncomeTaxWithholdingData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fdc/showIncomeTaxWithholding',
                    type: "GET",
                    data: {},
                    success: function (data) {
                        $('#IncomeTaxWithholdingData').html(data);
                        $('#supplier_id').select2();

                    }
                });
            }
            else { $('#IncomeTaxWithholdingData').html(''); }

            if ($('#SrbSindhRevenueBoard').is(':checked')) { $('.SrbSindhRevenueBoard').css('display', 'block'); }
            else { $('.SrbSindhRevenueBoard').css('display', 'none'); }
            if ($('#PunjabSalesTaxWithholding').is(':checked')) { $('.PunjabSalesTaxWithholding').css('display', 'block'); }
            else { $('.PunjabSalesTaxWithholding').css('display', 'none'); }

        }

        function FbrSalesTax() {
            if ($('#FbrSalesTaxWithholding').is(':checked')) {
                //$('.FbrSalesTaxWithholding').css('display','block');
                $('#FbrSalesTaxWithholdingData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fdc/showFbrSalesTaxWithholding',
                    type: "GET",
                    data: {},
                    success: function (data) {
                        $('#FbrSalesTaxWithholdingData').html(data);

                    }
                });
            }
            else { $('#FbrSalesTaxWithholdingData').html(''); }
        }

    </script>


    <script>
        $(".btn-success").click(function (e) {
            CheckDebitCredit();
            if (amount_check == 1) {
                alert("Amount Is Not Equal");
                return false;
            }
            var rvs = new Array();
            var val;
            $("input[name='pvsSection[]']").each(function () {
                rvs.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of rvs) {
                jqueryValidationCustom();
                if (validate == 0) {
                    //alert(response);
                } else {
                    return false;
                }
            }

        });

        amount_input = 2;
        function addAmount() {

            $('#addAmountTr').append(`

                    <tr class='AutoNo' id='remove_${amount_input}' >
                        <td>
                            <select class='form-control requiredField select2' onchange='Datavalidate(this)' name='account_id[]' id='account_id${amount_input}'>
                                <option value=''>Select Account</option>
                                <?php foreach (CommonHelper::get_all_account_operat_with_unique_code('2') as $Fil) {?>
                                    <option value='<?php    echo $Fil->id . ',0' . ',' . $Fil->code?>'><?php    echo $Fil->code . '--' . $Fil->name;?></option>
                                    <?php }?>
                            </select>
                        </td>
                        <td>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 addAmountRow" id="remove_${amount_input}">
                                <input placeholder="Debit" class="form-control d_amount_1" onfocus="d_amount_1_${amount_input}')" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_${amount_input}" onkeyup="sum('1')" value="">
                                <input class="form-control c_amount_1" type="hidden" name="c_amount[]"  />
                            </div>
                        </td>     
                        <td>
                            <span onclick="removeAmount('remove_${amount_input}')" class="btn-danger">X</span>
                        </td>
                 `);

            amount_input++;
            $('.select2').select2();

        }

        function removeAmount(div) {
            $("#" + div).remove();
            sum('1');

        }



    </script>


    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection