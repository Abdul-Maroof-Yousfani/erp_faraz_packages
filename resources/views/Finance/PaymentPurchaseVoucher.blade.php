<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$id = $_POST['checkbox'];
$UserId = Auth::user()->id;
//echo "<pre>";
//print_r($_POST);die;



?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <?php //if($UserId == 104):?>
    <div class="well_N">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Payment Voucher Through Purchase</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo Form::open(array('url' => '/AddPaymentPurchaseVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
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
                                                        <?php foreach($id as $row):
                                                        $purchase_voucher = DB::connection('mysql2')->table('new_purchase_voucher')->where('id', $row)->first();
                                                        //$purchase_voucher_data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('master_id', $row)
                                                          //      ->where('staus',1)->get();



                                                      $return_amount=  DB::Connection('mysql2')->table('purchase_return as a')
                                                            ->join('purchase_return_data as b','a.id','b.master_id')
                                                            ->where('a.status',1)
                                                            ->where('a.type',2)
                                                            ->where('grn_no',$purchase_voucher->grn_no)
                                                            ->sum('b.net_amount');

                                                        $purchase_voucher_data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('master_id', $row)
                                                                ->where('staus',1)
                                                                ->select(DB::raw('sum(net_amount) as totalamount'))
                                                                ->groupBy('master_id')
                                                                ->first();
                                                        $PurchaseAmount = CommonHelper::PurchaseAmountCheck($row);
                                                        $PurchaseAmount = $PurchaseAmount + $purchase_voucher->sales_tax_amount;
                                                        $purchase_voucher_payment_data = DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('new_purchase_voucher_id', $row)
                                                                ->where('status',1)
                                                                ->select(DB::raw('sum(amount) as totalamount'))
                                                                ->groupBy('pv_no')
                                                                ->first();
                                                        $paid_amt = isset($purchase_voucher_payment_data->totalamount)?$purchase_voucher_payment_data->totalamount:0;
                                                        $remainamount = $PurchaseAmount-$paid_amt-$return_amount;

                                                        $supplier_id = $purchase_voucher->supplier;
                                                        $supplier_name = CommonHelper::get_supplier_name($supplier_id);
                                                        $supplier_acc_id = CommonHelper::get_supplier_acc_id($supplier_id);
                                                           // print_r($purchase_voucher);
                                                        ?>
                                                        <input type="hidden" name="supplier_id" value="{{$supplier_id}}">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                                <div class="row">
                                                                    <input type="hidden" name="id[]" value="{{$purchase_voucher->id}}" />
                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">Purchase No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                        <input readonly type="text" class="form-control requiredField" name="pv_no{{$purchase_voucher->id}}" id="pv_no" value="{{$purchase_voucher->pv_no}}" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">Purchase Date.</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input readonly type="date" class="form-control requiredField" name="purchase_date{{$purchase_voucher->id}}" id="demand_date_1" value="{{$purchase_voucher->pv_date}}" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">Supplier Name<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                        <input readonly type="text" class="form-control requiredField" name="supplier{{$purchase_voucher->id}}" id="" value="{{$supplier_name}}" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                        <input readonly type="text" class="form-control" name="slip_no{{$purchase_voucher->id}}" id="slip_no_1" value="{{$purchase_voucher->slip_no}}" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">Bill Date.</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="date" class="form-control requiredField"  name="bill_date{{$purchase_voucher->id}}" id="bill_date" value="{{$purchase_voucher->bill_date}}" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">Amount<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                        <input type="text" class="form-control requiredField amount" onkeyup="sumed();CheckAmount('<?= $purchase_voucher->id; ?>');sum(1)" name="amount{{$purchase_voucher->id}}" id="amount{{$purchase_voucher->id}}" value="{{$remainamount}}" />
                                                                        <input type="hidden" id="existAmount{{$purchase_voucher->id}}" value="{{$remainamount}}" />
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="lineHeight">&nbsp;</div>
                                                        <?php
                                                            endforeach;
                                                            $pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),1);
                                                        ?>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="radio-inline"><input checked="checked" onclick="bank_cash()" type="radio" name="payment_type_mod" id="bank_radio" value="1">Bank</label>
                                                                        <label class="radio-inline"><input onclick="bank_cash()" type="radio" name="payment_type_mod" id="cash_radio" value="2">Cash</label>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <label class="sf-label">PV Date. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                        <input type="date" class="form-control requiredField" name="bpv_date" id="bpv_date" value="<?php echo date('Y-m-d'); ?>" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 payment_nature">
                                                                        <label class="sf-label">Cheque No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                        <input type="text" class="form-control requiredField" name="cheque_no" id="cheque_no_1" value="" />
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 payment_nature">
                                                                        <label class="sf-label">Cheque Date.</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="date" class="form-control requiredField"  name="cheque_date" id="cheque_date_1" value="<?php echo date('Y-m-d') ?>" />
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
                                                                                <option value="{{$y->id}}" @if($y->id == $supplier_acc_id) {{"selected"}} @endif >{{ $y->code .' ---- '. $y->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Debit" class="form-control d_amount_1 number_format" type="text" name="d_amount[]" id="d_amount_1_1" value="" onkeyup="sum('1')"  />
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Credit" class="form-control c_amount_1 number_format " type="text" name="c_amount[]" id="c_amount_1_1" value="" onkeyup="sum('1')" />
                                                                    </td>
                                                                    <td class="text-center">---</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <select style="width: 100%" class="form-control requiredField select2" name="account_id[]" id="account_id_1_2">
                                                                            <option value="">Select Account</option>
                                                                            @foreach($accounts as $key => $y)
                                                                                <option data-url="{{ $y->balance ?? 0 }}" value="{{ $y->id}}" @if($y->id == 87) selected @endif >
                                                                                    {{ $y->code .' ---- '. $y->name.' Balance ('.number_format($y->balance,2).')'}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input readonly placeholder="Debit" class="form-control d_amount_1 number_format" type="text" name="d_amount[]" id="d_amount_1_2" value="" onkeyup="sum('1')"  />
                                                                    </td>
                                                                    <td>
                                                                        <input placeholder="Credit" class="form-control c_amount_1 number_format" type="text" name="c_amount[]" id="c_amount_1_2" value="" onkeyup="sum('1')" readonly />
                                                                    </td>
                                                                    <td class="text-center">---</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <select style="width: 100%" class="form-control select2" name="account_id[]" id="account_id_1_3">
                                                                            <option value="">Select Account</option>
                                                                            @foreach(CommonHelper::get_gst_account() as $key => $y)
                                                                                <option value="{{ $y->id}}" data-value="{{$y->rate}}" >
                                                                                    {{$y->name}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input  placeholder="Debit" class="form-control d_amount_1 number_format" type="text" name="d_amount[]" id="d_amount_1_3" value="" onkeyup="sum('1')" readonly/>
                                                                    </td>
                                                                    <td>
                                                                        <input placeholder="Credit" class="form-control c_amount_1 number_format" type="text" name="c_amount[]" id="c_amount_1_3" value="" onkeyup="with_hold();sum('1')"/>
                                                                    </td>
                                                                    <td class="text-center">---</td>
                                                                </tr>

                                                                                    {{--For Tax Amir--}}

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
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                            <!-- <input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" /> -->
                                                        </div>


                                                    </div>
                                                    <?php
                                                    $WhereIn = implode(',',$id);
                                                    $Colll = DB::Connection('mysql2')->select('select pv_no,supplier from new_purchase_voucher where id in('.$WhereIn.')');
                                                         $count=count($Colll);
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="sf-label">Description</label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField">Paid Agst <?php $counter=1; foreach($Colll as $cc): if ($counter<=$count):echo '('.$counter.') ';endif; echo strtoupper($cc->pv_no).' '; $counter++;endforeach; ?><?php echo "\n".CommonHelper::get_supplier_name($cc->supplier);?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
                                                <div>&nbsp;</div>
                                                <div class="row">
                                                    <div style="text-align: center" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <button  type="submit" class="btn btn-sm btn-success submit" id="BtnApproved" >Submit</button>
                                                        <button type="submit" id="BtnSaveAndPrint" class="btn btn-sm btn-info BtnSaveAndPrint" >Save & Print</button>
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
        var x = 3;
        var x2=1;
        function AddMorePvs()
        {
            x++;

            $('#addMorePvsDetailRows_1').append("<tr id='tr"+x+"' >"+
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+x+"'><option value=''>Select Account</option><?php foreach($accounts as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "<td>"+
                    '<input  placeholder="Debit" class="form-control  d_amount_'+x2+' requiredField" onfocus="mainDisable('+$.trim("'c_amount_1_"+x+"','d_amount_1_"+x+"'")+')" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_'+x+'" onkeyup="sum('+$.trim("'"+x2+"'")+')" value="" />'+
                    "</td>"+
                    "<td>"+
                    '<input  placeholder="Credit" class="form-control c_amount_'+x2+' requiredField " onfocus="mainDisable('+$.trim("'d_amount_1_"+x+"','c_amount_1_"+x+"'")+')" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_'+x+'" onkeyup="sum('+$.trim("'"+x2+"'")+')" value="" />'+
                    "</td>"+
                    "<td class='text-center'> <input type='button' onclick='RemoveSection("+x+")' value='Remove' class='btn btn-sm btn-danger'> </td></tr>");
            $('.select2').select2();


            // $('.d_amount_1_3').number(true,2);
        }

        function RemoveSection(Row) {
            //            alert(Row);
            $('#tr' + Row).remove();
            //   $(".AutoCounter").html('');
            var AutoCount = 1;
            var AutoCount = 1;;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).prop('title', AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }

        $(document).ready(function(){
            $('.select2').select2();

            $(".submit").click(function(e){
                $('#SavePrintVal').val('0');
                CheckDebitCredit();
                if(amount_check==1)
                {
                    alert("Amount Is Not Equal");
                    return false;
                }
                var pvs = new Array();
                var val;
                $("input[name='pvsSection[]']").each(function(){
                    pvs.push($(this).val());
                });
                var _token = $("input[name='_token']").val();


                for (val of pvs) {
                    jqueryValidationCustom();

                    var DebitTotal = $('#d_t_amount_1').val();
                    var CreditTotal = $('#c_t_amount_1').val();
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


            $(".BtnSaveAndPrint").click(function(e){
                $('#SavePrintVal').val('1');
                CheckDebitCredit();
                if(amount_check==1)
                {
                    alert("Amount Is Not Equal");
                    return false;
                }
                var pvs = new Array();
                var val;
                $("input[name='pvsSection[]']").each(function(){
                    pvs.push($(this).val());
                });
                var _token = $("input[name='_token']").val();


                for (val of pvs) {
                    jqueryValidationCustom();

                    var DebitTotal = $('#d_t_amount_1').val();
                    var CreditTotal = $('#c_t_amount_1').val();
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

            sumed();
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
            $('#d_amount_1_1').val(total);
            $('#d_t_amount_1').val(total);
            $('#c_amount_1_2').val(total);
            $('#c_t_amount_1').val(total);


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
           $("#account_id_1_3").trigger('change')

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
              $("#account_id_1_2").val(69).trigger('change');
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

        $(document).ready(function(){
            $("#account_id_1_3").change(function() {
                    // const bankCreditValue = Number($('#amount2').val());
                    // Get the selected option
                    let selectedOption = $(this).find("option:selected");

                    // Get the data-value attribute
                    let dataValue = + selectedOption.data("value");
                    let amount = 0 ;// + $('#amount2').val();

                    $('.amount').each(function() {
                        amount += Number($(this).val())
                    });
                    if(dataValue)
                    {


                        let percentage = ( amount * dataValue ) / 100;


                        let creditMinusPercentage = amount - percentage;

                        $('#c_amount_1_2').val(creditMinusPercentage)

                        $('#c_amount_1_3').val(percentage);
                    }
                    else
                    {
                        $('#c_amount_1_2').val(amount)

                    }

                });
                // $("#account_id_1_3").trigger('change')
            });

            function with_hold()
            {
               var with_hold =  $('#c_amount_1_3').val();
               var debit =  $('#d_amount_1_1').val();
               var total = debit - with_hold;
               $('#c_amount_1_2').val(total);
            }
    </script>
    <?php //else:?>
    {{--<div class="well">--}}
        {{--<div class="panel">--}}
            {{--<div class="panel-body">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                        {{--<div class="well">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                                    {{--<span class="subHeadingLabelClass">Work In Progress</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <?php //endif;?>

@endsection
