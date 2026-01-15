<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

//echo "<pre>";
//print_r($new_purchase_voucher_payment);
//die;

?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="well well_N">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit Payment Voucher Thorough Purchase</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo Form::open(array('url' => '/updatePaymentPurchaseVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="panel">
                                            <div class="panel-body">

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <?php $new_pv_data_amt1=0;$new_pv_data_amt2=0;$new_pv_data_amt3=0;
                                                        foreach($new_purchase_voucher_payment as $pv_no):
                                                        $new_purchase_voucher_payment_id = $pv_no->id;
                                                        $new_pv_id = $pv_no->new_pv;
                                                        $new_pv_no = $pv_no->new_pv_no;

                                                        $new_pv = DB::connection('mysql2')->table('new_pv')->where('id', $pv_no->new_pv)->where('status','=','1')->first();
                                                        $new_pv_data = DB::connection('mysql2')->table('new_pv_data')->where('master_id', $pv_no->new_pv)->where('status','=','1')->orderBy('id', 'asc')->get();

                                                        $data=1;$acc_id1='';$acc_id2='';$acc_id3='';
                                                        foreach($new_pv_data as $val):
                                                            if($data==1):


                                                                $acc_id1 = $val->acc_id;    $new_pv_data_amt1 = $val->amount;
                                                            elseif($data==2): $acc_id2 = $val->acc_id; $new_pv_data_amt2 = $val->amount;
                                                            elseif($data==3): $acc_id3 = $val->acc_id; $new_pv_data_amt3 = $val->amount;
                                                            endif;
                                                            $data++;
                                                        endforeach;

                                                        $payment_type = $new_pv->payment_type;
                                                        $pv_date = $new_pv->pv_date;
                                                        $cheque_no = $new_pv->cheque_no;
                                                        $cheque_date = $new_pv->cheque_date;
                                                        $description = $new_pv->description;

                                                        $purchase_voucher = DB::connection('mysql2')->table('new_purchase_voucher')->where('pv_no', $pv_no->pv_no)->first();
                                                        $purchase_voucher_data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('pv_no', $pv_no->pv_no)
                                                                ->where('staus',1)
                                                                ->select(DB::raw('sum(amount) as totalamount'))
                                                                ->groupBy('master_id')
                                                                ->first();
                                                        $purchase_voucher_payment_data = DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('pv_no', $pv_no->pv_no)
                                                                ->where('status',1)
                                                                ->select(DB::raw('sum(amount) as totalamount'))
                                                                ->groupBy('pv_no')
                                                                ->first();

                                                        $amount = $pv_no->amount;
                                                        $purchase_amount = $purchase_voucher_data->totalamount;
                                                        $paid_amount = $purchase_voucher_payment_data->totalamount;
                                                        $total_remain_amount = $purchase_amount-$paid_amount+$amount;

                                                        $supplier_id = $purchase_voucher->supplier;
                                                        $supplier_name = CommonHelper::get_supplier_name($supplier_id);
                                                        $supplier_acc_id = CommonHelper::get_supplier_acc_id($supplier_id);
                                                        // print_r($purchase_voucher);
                                                        ?>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                            <div class="row">
                                                                <input type="hidden" name="new_purchase_voucher_payment_id[]" value="{{$new_purchase_voucher_payment_id}}" />
                                                                <input type="hidden" name="new_pv_id" value="{{$new_pv_id}}" />
                                                                <input type="hidden" name="new_pv_no" value="{{$new_pv_no}}" />
                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Purchase No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input readonly type="text" class="form-control requiredField" name="pv_no{{$new_purchase_voucher_payment_id}}" id="pv_no" value="{{$purchase_voucher->pv_no}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Purchase Date.</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input readonly type="date" class="form-control requiredField" name="purchase_date{{$new_purchase_voucher_payment_id}}" id="demand_date_1" value="{{$purchase_voucher->pv_date}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Supplier Name<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input readonly type="text" class="form-control requiredField" name="supplier{{$new_purchase_voucher_payment_id}}" id="" value="{{$supplier_name}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input readonly type="text" class="form-control" name="slip_no{{$new_purchase_voucher_payment_id}}" id="slip_no_1" value="{{$purchase_voucher->slip_no}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Bill Date.</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input readonly type="date" class="form-control requiredField"  name="bill_date{{$new_purchase_voucher_payment_id}}" id="bill_date" value="{{$purchase_voucher->bill_date}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Purchase Amount<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input type="text" class="form-control requiredField" readonly value="{{$purchase_amount}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Paid Amount<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input type="text" class="form-control requiredField" readonly value="{{$paid_amount}}" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">Amount<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input type="text" class="form-control requiredField amount" onkeyup="sumed();CheckAmount('<?= $new_purchase_voucher_payment_id; ?>')" name="amount{{$new_purchase_voucher_payment_id}}" id="amount{{$new_purchase_voucher_payment_id}}" value="{{$amount}}" />
                                                                    <span>{{'('.($total_remain_amount-$amount).')'}}</span>
                                                                    <input type="hidden" id="existAmount{{$new_purchase_voucher_payment_id}}" value="{{$total_remain_amount}}" />
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="lineHeight">&nbsp;</div>
                                                        <?php
                                                        endforeach;
                                                        ?>

                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="row">

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="sf-label">PV Date. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input type="date" class="form-control requiredField" name="bpv_date" id="bpv_date" value="<?php echo $pv_date; ?>" />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="radio-inline"><input @if($payment_type==1): checked="checked" @else disabled @endif onclick="bank_cash()" type="radio" name="payment_type_mod" id="bank_radio" value="1" readonly>Bank</label>
                                                                    <label class="radio-inline"><input @if($payment_type==2): checked="checked" @else disabled @endif onclick="bank_cash()" type="radio" name="payment_type_mod" id="cash_radio" value="2" readonly>Cash</label>
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 payment_nature">
                                                                    <label class="sf-label">Cheque No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                    <input type="text" class="form-control requiredField" name="cheque_no" id="cheque_no_1" value="<?= $cheque_no ?>" readonly />
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 payment_nature">
                                                                    <label class="sf-label">Cheque Date.</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input type="date" class="form-control requiredField"  name="cheque_date" id="cheque_date_1" value="<?= $cheque_date ?>" readonly />
                                                                </div>



                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table id="buildyourform" class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                                                                    </th>
                                                                    <th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    <th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    <th class="text-center" style="width:150px;">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                                                <?php
                                                                $accounts = CommonHelper::get_all_account();
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <select style="width: 100%" class="form-control requiredField select2" name="account_id[]" id="account_id_1_1">
                                                                            <option value="">Select Account</option>
                                                                            @foreach($accounts as $key => $y)
                                                                                <option value="{{$y->id}}" @if($y->id == $acc_id1) {{"selected"}} @endif >{{ $y->code .' ---- '. $y->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Debit" class="form-control requiredField" type="text" name="d_amount" id="d_amount" value="{{$new_pv_data_amt1}}" />
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Credit" class="form-control requiredField " type="text" name="c_amount[]" id="" value="" />
                                                                    </td>
                                                                    <td class="text-center">---</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <select style="width: 100%" class="form-control requiredField select2" name="account_id[]" id="account_id_1_2">
                                                                            <option value="">Select Account</option>
                                                                            @foreach($accounts as $key => $y)
                                                                                <option value="{{ $y->id}}" @if($y->id == $acc_id2) {{"selected"}} @endif >{{ $y->code .' ---- '. $y->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Debit" class="form-control requiredField" type="text" name="" id="" value="" />
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Credit" class="form-control requiredField " type="text" name="c_amount[]" id="c_amount" value="{{$new_pv_data_amt2}}" />
                                                                    </td>
                                                                    <td class="text-center">---</td>
                                                                </tr>

                                                                {{--For Tax Amir--}}
                                                                <tr>
                                                                    <td>
                                                                        <select style="width: 100%" class="form-control requiredField select2" name="account_id[]" id="account_id_1_3">
                                                                            <option value="0">Select Account</option>
                                                                            @foreach($accounts as $key => $y)
                                                                                <option value="{{ $y->id}}" @if($y->id == $acc_id3) {{"selected"}} @endif >{{ $y->code .' ---- '. $y->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Debit" class="form-control requiredField" type="text" name="" id="" value="" />
                                                                    </td>
                                                                    <td>
                                                                        <input onkeyup="income_tax()"  placeholder="Credit" class="form-control  " type="text" name="c_amount[]" id="tax" value="{{$new_pv_data_amt3}}" />
                                                                    </td>
                                                                    <td class="text-center">---</td>
                                                                </tr>

                                                                {{--For Tax--}}

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
                                                                                id="total_debit"
                                                                                maxlength="15"
                                                                                min="0"
                                                                                name=""
                                                                                class="form-control  text-right number_format"
                                                                                value=""/>
                                                                    </td>
                                                                    <td style="width:150px;">
                                                                        <input
                                                                                type="number"
                                                                                readonly="readonly"
                                                                                id="total_credit"
                                                                                maxlength="15"
                                                                                min="0"
                                                                                name=""
                                                                                class="form-control  text-right number_format"
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

                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="sf-label">Description</label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField">{{ $description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>&nbsp;</div>
                                                <div class="row">
                                                    <div style="text-align: center" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <button  type="submit" class="btn btn-sm btn-success submit" id="BtnApproved" >Submit</button>
                                                    </div>
                                                </div>
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
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
            bank_cash();

            $(".submit").click(function(e){

                var pvs = new Array();
                var val;
                $("input[name='pvsSection[]']").each(function(){
                    pvs.push($(this).val());
                });
                var _token = $("input[name='_token']").val();


                for (val of pvs) {
                    jqueryValidationCustom();


                    if(validate == 0)
                    {
//                        $("#account_id_1_1").prop('disabled', false);
//                        $(".d_amount_1").prop('disabled', false);
//                        $(".c_amount_1").prop('disabled', false);

                    }
                    else
                    {
                        return false;
                    }
                }

            });

            // sumed();
            //toWords();
        });

        function sumed()
        {
            var sum = 0;
            var total = 0;
            $('.amount').each(function() {
                sum = Number($(this).val());
                total = sum+total;
            });
            //alert(total);
            $('#d_amount').val(total);
            $('#total_debit').val(total);
            $('#c_amount').val(total);
            $('#total_credit').val(total);
            income_tax();
            // $("#hidden_third_debit").val(total);
        }

        function CheckAmount(id)
        {
            existAmount = parseFloat($("#existAmount"+id).val());
            amount = parseFloat($("#amount"+id).val());

            if(amount > existAmount)
            {
                alert("Amount Cannot exceed"+existAmount);
                $("#amount"+id).val(existAmount.toFixed(2));
                sumed();
            }
        }


        function bank_cash()
        {
            if ($("#bank_radio").prop("checked"))
            {

                $('.payment_nature').fadeIn(500);
                $("#cheque_no_1").addClass("requiredField");
                //   $("#account_id_1_2").val(113).trigger('change');

            }
            else
            {
                $('.payment_nature').fadeOut(500);
                $("#cheque_no_1").removeClass("requiredField");
                $("#cheque_date_1").removeClass("requiredField");
                $("#account_id_1_2").val(106).trigger('change');
                //    $("#account_id_1_2").val(109).trigger('change');
            }
        }

        function income_tax()
        {
            var amount=   $('#d_amount').val();
            var tax=$('#tax').val();
            var total=parseFloat(amount-tax);

            $('#c_amount').val(total);
        }
    </script>

@endsection
