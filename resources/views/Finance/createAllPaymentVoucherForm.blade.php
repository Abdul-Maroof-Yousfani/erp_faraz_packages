<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),2);
?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="headquid"><span class="subHeadingLabelClass">Create All Payment Voucher Form</span></div>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo Form::open(array('url' => '/insertAllPayment?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                        </div>
                                    </div>

                                    {{--                                    <div class="row">--}}
                                    {{--                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                                    {{--                                            <label for="">Advance Payment <input type="checkbox" name="advance_payment" id="advance_payment" value="1"></label>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h5 class="font-weight-bold">
                                                <b>Voucher Detail</b>
                                            </h5>
                                        </div>
                                        {{--                                        <input type="hidden" name="type" id="type" value="1" />--}}
                                        {{--                                        <input  checked  type="hidden" class="" value="1" name="payment_mod"  />--}}

                                        {{--                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">--}}
                                        {{--                                            <label class="sf-label">Voucher No</label>--}}
                                        {{--                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>--}}
                                        {{--                                            <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"--}}
                                        {{--                                                    name="" id="" value="{{strtoupper($pv_no)}}" />--}}
                                        {{--                                        </div>--}}

                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="sf-label">Date.</label>
                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date_1" id="pv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 hide">
                                            <label class="sf-label">PV Day.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input  readonly type="text" class="form-control"  name="pv_day" id="pv_day"  />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="sf-label">Ref / Bill No.</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <input   type="text" class="form-control" placeholder="Slip No" name="slip_no_1" id="slip_no_1" value="-" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="sf-label">Bill Date.</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField"  name="bill_date" id="bill_date" value="" />
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h5 class="font-weight-bold">
                                                <b> Payment Detail</b>
                                            </h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Payment type</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control requiredField" name="payment_type" id="payment_type" required>
                                                <option value="">Select Payment Type</option>
                                                <option value="2">Cash</option>
                                                <option value="1">Bank</option>
                                            </select>
                                        </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bankfields"  style="display: none;">
                                                <label class="sf-label">Cheque No.</label>
                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                <input type="text" class="form-control" placeholder="Cheque No" name="cheque_no_1" id="cheque_no" value="">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bankfields"  style="display: none;">
                                                <label class="sf-label">Cheque Date.</label>
                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" name="cheque_date_1" id="cheque_date_1" value="2024-03-06">
                                            </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Payment Mode</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control requiredField select2" id="payment_mode" name="paid_to_type" onchange="getPvMergeChunk()" >
                                                <option value="">Select payment Mode</option>
                                                <option value="2">Supplier Payment</option>
                                                <option value="0">Expense Payment</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ifpaymentmodeissupplier" style="display: none">
                                            <label class="sf-label">Payment For</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control requiredField select2" name="payment_for" id="payment_for" onchange="getPvMergeChunk()" >
                                                <option value="">Select</option>
                                                <option value="1">Advance</option>
                                                <option value="0">Invoice</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ifpaymentmodeissupplier" style="display: none">
                                            <label class="sf-label">Party Details</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control requiredField select2" onchange="getPvMergeChunk()" name="paid_to" id="party_detail">
                                                <option value="">Select Account</option>
                                                @foreach(CommonHelper::get_all_supplier() as $row)
                                                    <option value="{{$row->id.','.$row->acc_id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            {{--                                            <input type="text" class="form-control requiredField" placeholder="Party Details" name="party_detail" id="party_detail">--}}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ifpaymentmodeisexpense" style="display: none">
                                            <label class="sf-label">Payment From</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <select style="width: 100%" class="form-control requiredField select2" id="payment_from" name="payment_from">
                                                <option value="">Select Account</option>
                                                @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                                                    <option value="{{ $y->id.',0'}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>
                                    <hr>


                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            {{--<div class="row">--}}
                                            {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">--}}
                                            {{--<input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" />--}}
                                            {{--</div>--}}
                                            {{--</div>--}}

                                            <div class='jhed' id="appendData">
                                                <div class='jhed headquid'>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="subHeadingLabelClass">Cash Payment Voucher Detail</span>
                                                        </div>

                                                        <div class="col-md-6 text-right">
                                                            <input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" />
                                                            <span class="badge badge-success" id="span">2</span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="table-responsive">
                                                    <table id="buildyourform" class="userlittab table table-bordered sf-table-list">
                                                        <thead>

                                                        <tr>
                                                            <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                                                            <th class="text-center hide" style="width:450px;">Description <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center" style="width:150px;">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                                        <?php for($j = 1 ; $j <= 2 ; $j++){?>
                                                        <input type="hidden" name="rvsDataSection_1[]" class="form-control" id="rvsDataSection_1" value="<?php echo $j?>" />
                                                        <tr class="AutoNo">
                                                            <td>
                                                                <select style="width: 100%" class="form-control requiredField select2 acccccctex" name="account_id[]" id="account_id{{$j}}">
                                                                    <option value="">Select Account</option>
                                                                    @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                                                                        <option value="{{ $y->id.',0'}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                                    @endforeach
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
                                                                <input
                                                                        type="number"
                                                                        readonly="readonly"
                                                                        id="d_t_amount_1"
                                                                        maxlength="15"
                                                                        min="0"
                                                                        name="d_t_amount_1"
                                                                        class="form-control requiredField text-right number_format"
                                                                        value=""/>
                                                            </td>
                                                            <td style="width:150px;">
                                                                <input
                                                                        type="number"
                                                                        readonly="readonly"
                                                                        id="c_t_amount_1"
                                                                        maxlength="15"
                                                                        min="0"
                                                                        name="c_t_amount_1"
                                                                        class="form-control requiredField text-right number_format"
                                                                        value=""/>
                                                            </td>
                                                            <td class="diff" style="width:150px;font-size: 20px;">
                                                                <input readonly style="color: blue;font-weight: 600" class="form-control" type="text" id="diff" value=""/>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

<div class="accountheadchunk">

</div>



                                </div>
                            </div>
                        </div>
                        <div class="pvsSection"></div>
                        <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <div class="headquid">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <button type="submit" id="BtnSaveAndPrint" class="btn btn-sm btn-info BtnSaveAndPrint" >Save & Print</button>
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
        $(document).ready(function() {
            $('#payment_from').change(function() {
                var selectedValue = $(this).val();
                $('.acccccctex').val(selectedValue).trigger('change');
            });
            $('#BtnSaveAndPrint').on('click' , function(){
                $('#SavePrintVal').val('1');
            })
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
            //$('.number_format').number(true,2);

            $(document).ready(function(){
                $('select[name="payment_type"]').change(function(){
                    var selectedPaymentMode = $(this).val();
                    if (selectedPaymentMode == "1") {
                        $('.bankfields').fadeIn('slow');
                        $('.bankfields input').addClass('requiredField');
                    } else {
                        $('.bankfields').fadeOut('slow');
                        $('.bankfields input').removeClass('requiredField');

                    }
                });

                $('select[name="paid_to_type"]').change(function(){
                    var selectedPaymentMode = $(this).val();
                    if(selectedPaymentMode == "2") {
                        // $('select[name="payment_from"]').fadeOut();
                        $('.ifpaymentmodeissupplier').fadeIn();
                        $('.ifpaymentmodeissupplier select').addClass('requiredField');
                        $('.ifpaymentmodeisexpense').fadeOut();
                        $('.ifpaymentmodeisexpense select').removeClass('requiredField');
                    } else {
                        $('.ifpaymentmodeissupplier').fadeOut();
                        $('.ifpaymentmodeissupplier select').removeClass('requiredField');
                        $('.ifpaymentmodeisexpense').fadeIn();
                        $('.ifpaymentmodeisexpense select').addClass('requiredField');

                    }
                });
            });
        });
    </script>

    <script>
        var x = 2;
        var x2=1;
        function AddMorePvs()
        {
            x++;

            $('#addMorePvsDetailRows_1').append("<tr id='tr"+x+"' class='AutoNo'>"+
                "<td>"+
                "<select style='width: 100%' class='form-control requiredField select2' name='account_id[]' id='account_id"+x+"'><option value=''>Select Account</option><?php foreach(CommonHelper::get_all_account_operat() as $Fil){?><option value='<?php echo $Fil->id.',0'?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                "</td>"+
                "<td class='hide'>"+
                '<textarea class="form-control" name="desc[]" id="desc_1_'+x+'"/> </textarea>'+
                "</td>"+
                "<td>"+
                '<input  placeholder="Debit" class="form-control d_amount_'+x2+' requiredField number_format" onfocus="mainDisable('+$.trim("'c_amount_1_"+x+"','d_amount_1_"+x+"'")+')" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_'+x+'" onkeyup="sum('+$.trim("'"+x2+"'")+')" value="" required="required"/>'+
                "</td>"+
                "<td>"+
                '<input  placeholder="Credit" class="form-control c_amount_'+x2+' requiredField number_format" onfocus="mainDisable('+$.trim("'d_amount_1_"+x+"','c_amount_1_"+x+"'")+')" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_'+x+'" onkeyup="sum('+$.trim("'"+x2+"'")+')" value="" required="required"/>'+
                "</td>"+
                "<td class='text-center'> <input type='button' onclick='RemoveRow("+x+")' value='Remove' class='btn btn-sm btn-danger'> </td></tr>");
            $('.select2').select2();
            //$('.number_format').number(true,2);
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }


        function RemoveRow(x)
        {
            $('#tr'+x).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }
    </script>
    <script !src="">


    </script>


    <script>
        $(".btn-success").click(function(e){
            CheckDebitCredit();
            if(amount_check==1)
            {
                alert("Amount Is Not Equal");
                return false;
            }
            var rvs = new Array();
            var val;
            $("input[name='pvsSection[]']").each(function(){
                rvs.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of rvs) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


        function getPvMergeChunk()
        {

            var Supplier = $('select[name="paid_to"]').val();
            var payment_for = $('select[name="payment_for"]').val();
            var paid_to_type = $('select[name="paid_to_type"]').val();
            var m = '<?php echo $_GET['m'];?>';
            console.log(paid_to_type == 3);

            if (paid_to_type == 3 && (payment_for == '' || Supplier == '')) {
                // Alert the user or handle the case where the required selections are not made
                alert("Please select payment for and supplier.");
                return; // Exit the function without sending AJAX request
            }



            $('#appendData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/get_pv_merge_chunk',
                type: 'Get',
                data: {Supplier:Supplier,payment_for:payment_for,paid_to_type:paid_to_type,m:m},

                success: function (response) {
                    $('#appendData').html(response);
                    // $('#data').html(response);
                    // $('#FromShow').html(FromShow);
                    // $('#ToShow').html(ToShow);
                    // $('#ShowTitle').css('display','block');

                }
            });
        }



        function pvAcountHeadPoPiChunk()
        {
            var checkedValues = []; // Array to store checked values

            var po_or_pi = $('.checkbox1').val();
            var m = '<?php echo $_GET['m'];?>';
            console.log(po_or_pi);
var purchase_voucher_type  =   $('input[name="purchase_voucher_type"]').val();

            $('.checkbox1:checked').each(function() {
                checkedValues.push($(this).val());
            });
            // $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            $('.accountheadchunk').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/pv_acount_head_po_pi_chunk',
                type: 'Get',
                data: {po_or_pi:checkedValues,m:m,purchase_voucher_type:purchase_voucher_type},

                success: function (response) {
                    $('.accountheadchunk').html(response);
                    // $('#data').html(response);
                    // $('#FromShow').html(FromShow);
                    // $('#ToShow').html(ToShow);
                    // $('#ShowTitle').css('display','block');

                }
            });
        }


    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


    {{--    get_pv_merge_chunk--}}



@endsection
