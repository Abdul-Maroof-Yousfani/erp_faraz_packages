<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$purchase_voucher_type = $_GET['purchase_voucher_type'];
$UserId = Auth::user()->id;

?>
@if(!isset($_GET['po_or_pi']))
    <div class="alert alert-danger" role="alert">
        Please ensure that you have correctly checkmark atleast one Invoice/Order.
    </div>
@else
        <?php  $id = $_GET['po_or_pi'];?>
    @if($purchase_voucher_type == 1)
        @include('select2')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">


                    <div class="lineHeight">&nbsp;</div>
                    <div id="printBankPaymentVoucherList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                                    $grn = DB::connection('mysql2')->table('new_purchase_voucher')->whereIn('id', $id)->get()->pluck('grn_no');
                                                    $sales_tax_amount = DB::connection('mysql2')->table('new_purchase_voucher')->whereIn('id', $id)->sum('sales_tax_amount');
                                                    $return_amount=  DB::Connection('mysql2')->table('purchase_return as a')
                                                        ->join('purchase_return_data as b','a.id','b.master_id')
                                                        ->where('a.status',1)
                                                        ->where('a.type',2)
                                                        ->whereIn('grn_no',$grn)
                                                        ->sum('b.net_amount');

                                                    $purchase_voucher_data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('master_id', $row)
                                                        ->where('staus',1)
                                                        ->select(DB::raw('sum(net_amount) as totalamount'))
                                                        ->groupBy('master_id')
                                                        ->first();
                                                    $PurchaseAmount = CommonHelper::PurchaseAmountCheck($id);
                                                    $PurchaseAmount = $PurchaseAmount + $sales_tax_amount;
                                                    $purchase_voucher_payment_data = DB::connection('mysql2')->table('new_purchase_voucher_payment')->whereIn('new_purchase_voucher_id', $id)
                                                        ->where('status',1)
                                                        ->where('purchase_voucher_type',1)
                                                        ->select(DB::raw('sum(amount) as totalamount'))
                                                        ->first();

                                                    $paid_amt = isset($purchase_voucher_payment_data->totalamount)?$purchase_voucher_payment_data->totalamount:0;
                                                    $remainamount = $PurchaseAmount-$paid_amt-$return_amount;
                                                    ?>



                                                    <?php
                                                    $purchase_voucher = DB::connection('mysql2')->table('new_purchase_voucher')->whereIn('id', $id)->first();
                                                    $supplier_id = $purchase_voucher->supplier;
                                                    $supplier_name = CommonHelper::get_supplier_name($supplier_id);
                                                    $supplier_acc_id = CommonHelper::get_supplier_acc_id($supplier_id);


                                                    ?>



                                                <div class="lineHeight">&nbsp;</div>
                                                <?php
                                                endforeach;
                                                    $pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),1);
                                                    ?>


                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="buildyourform" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head (P.I)</a>

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
                                                                <input readonly placeholder="Debit" class="form-control d_amount_1 number_format" type="text" name="d_amount[]" id="d_amount_1_1" value="{{$remainamount}}" onkeyup="sum('1')"  />
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
                                                                    @foreach(CommonHelper::get_all_account_operat_with_unique_code('1-2-8') as $key => $y)
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
                                                                <input type="hidden" id="totalamount" value="{{$remainamount}}">
                                                                <input placeholder="Credit" class="form-control c_amount_1 number_format" type="text" name="c_amount[]" id="c_amount_1_2" value="{{$remainamount}}" onkeyup="sum('1')" readonly />
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
                                                                <input type="hidden" id="existAmount37" value="50700">
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
                                        <div class="row hide">
                                            <div style="text-align: center" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <button  type="submit" class="btn btn-sm btn-success submit" id="BtnApproved" >Submit</button>
                                                <button type="submit" id="BtnSaveAndPrint" class="btn btn-sm btn-info BtnSaveAndPrint" >Save & Print</button>
                                            </div>
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




            $(document).ready(function(){
                $('.select2').select2();




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
                // $('.amount').each(function() {
                //     sum = Number($(this).val());
                //     total = sum+total;
                // });


                total = $('#totalamount').val();
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
                    console.log(selectedOption);
                    // Get the data-value attribute
                    let dataValue = + selectedOption.data("value");
                    let amount = 0 ;// + $('#amount2').val();
                    amount = $('#totalamount').val();
                    // $('.amount').each(function() {
                    //     amount += Number($(this).val())
                    // });
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
    @endif
    @if($purchase_voucher_type == 2)
        @include('select2')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">


                    <div class="lineHeight">&nbsp;</div>
                    <div id="printBankPaymentVoucherList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

                                                    // old table was purchase_request in both var  purchase_voucher   sales_tax_amount
                                                    $purchase_voucher = DB::connection('mysql2')->table('purchase_request')->where('id', $row)->first();


                                                    $sales_tax_amount = DB::connection('mysql2')->table('purchase_request')->whereIn('id', $id)->sum('sales_tax_amount');

                                                    $purchase_voucher_data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('master_id', $row)
                                                        ->where('staus',1)
                                                        ->select(DB::raw('sum(net_amount) as totalamount'))
                                                        ->groupBy('master_id')
                                                        ->first();

                                                    $PurchaseAmount= ReuseableCode::get_po_total_amount($id);



                                                    $PurchaseAmount = $PurchaseAmount + $sales_tax_amount;



                                                    $purchase_voucher_payment_data = DB::connection('mysql2')->table('new_purchase_voucher_payment')->whereIn('new_purchase_voucher_id', $id)
                                                        ->where('status',1)
                                                        ->where('purchase_voucher_type',2)
                                                        ->select(DB::raw('sum(amount) as totalamount'))
                                                        ->first();

                                                    $paid_amt = isset($purchase_voucher_payment_data->totalamount)?$purchase_voucher_payment_data->totalamount:0;
                                                    $remainamount = $PurchaseAmount-$paid_amt;
                                                
//                                            $supplier_id = $purchase_voucher->supplier;
//                                            $supplier_name = CommonHelper::get_supplier_name($supplier_id);
//                                            $supplier_acc_id = CommonHelper::get_supplier_acc_id($supplier_id);
                                                    // print_r($purchase_voucher);
                                                    ?>



                                                    <?php
                                                    $purchase_voucher = DB::connection('mysql2')->table('purchase_request')->whereIn('id', $id)->first();
                                                    $supplier_id = $purchase_voucher->supplier_id;
                                                    $supplier_name = CommonHelper::get_supplier_name($supplier_id);
                                                    $supplier_acc_id = CommonHelper::get_supplier_acc_id($supplier_id);


                                                    ?>



                                                <div class="lineHeight">&nbsp;</div>
                                                <?php
                                                endforeach;
                                                    $pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),1);
                                                    ?>


                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="buildyourform" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head  (P.O)</a>

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
                                                                <input readonly placeholder="Debit" class="form-control d_amount_1 number_format" type="text" name="d_amount[]" id="d_amount_1_1" value="{{$remainamount}}" onkeyup="sum('1')"  />
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
                                                                    @foreach(CommonHelper::get_all_account_operat_with_unique_code('1-2-8') as $key => $y)
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
                                                                <input type="hidden" id="totalamount" value="{{$remainamount}}">
                                                                <input placeholder="Credit" class="form-control c_amount_1 number_format" type="text" name="c_amount[]" id="c_amount_1_2" value="{{$remainamount}}" onkeyup="sum('1')" readonly />
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
                                                                <input type="hidden" id="existAmount37" value="50700">
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
                                                $Colll = DB::Connection('mysql2')->select('select purchase_request_no,supplier_id from purchase_request where id in('.$WhereIn.')');
                                                $count=count($Colll);
                                                ?>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="sf-label">Description</label>
                                                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                        <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField">Paid Agst <?php $counter=1; foreach($Colll as $cc): if ($counter<=$count):echo '('.$counter.') ';endif; echo strtoupper($cc->purchase_request_no).' '; $counter++;endforeach; ?><?php echo "\n".CommonHelper::get_supplier_name($cc->supplier_id);?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
                                        <div>&nbsp;</div>
                                        <div class="row hide">
                                            <div style="text-align: center" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <button  type="submit" class="btn btn-sm btn-success submit" id="BtnApproved" >Submit</button>
                                                <button type="submit" id="BtnSaveAndPrint" class="btn btn-sm btn-info BtnSaveAndPrint" >Save & Print</button>
                                            </div>
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




            $(document).ready(function(){
                $('.select2').select2();




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
                // $('.amount').each(function() {
                //     sum = Number($(this).val());
                //     total = sum+total;
                // });


                total = $('#totalamount').val();
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
                    console.log(selectedOption);
                    // Get the data-value attribute
                    let dataValue = + selectedOption.data("value");
                    let amount = 0 ;// + $('#amount2').val();
                    amount = $('#totalamount').val();
                    // $('.amount').each(function() {
                    //     amount += Number($(this).val())
                    // });
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
    @endif
@endif

