<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\SalesHelper;
        use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('number_formate')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Bank Receipt Voucher Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'fad/addBankReceiptVoucherDetail_against_sales?m='.$m.'','id'=>'bankReceiptVoucherForm'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="sales" value="1">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="rvsSection[]" class="form-control requiredField" id="rvsSection" value="1" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Ref / Bill No.</label>
                                                                <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>

                                                                @foreach($val as $row)
                                                                    <?php

                                                                    $data=SalesHelper::get_invoice_number_amount($row);
                                                                    $branch_id = $data->branch_id; ?>
                                                                    <?php $client_accid=SalesHelper::get_client_by_id($data->bill_to_client_id); ?>
                                                                    <input type="hidden" name="ids[]" value="{{$row}}">
                                                                    <input readonly type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no[]" id="slip_no_1" value="{{$data->inv_no}}" />
                                                                    <input type="hidden" name="slipno" id="slipno" value="{{$data->inv_no}}" />

                                                                @endforeach
                                                                <?php //$buers_id=$data->buyers_id; ?>
                                                            </div>

                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Client Ref No.</label>
                                                                @foreach($val as $row)
                                                                    <?php $data=SalesHelper::get_invoice_number_amount($row); ?>
                                                                    <input readonly type="text" class="form-control" value="{{$data->client_ref}}" />
                                                                @endforeach
                                                            </div>

                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Amount</label>
                                                                <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                                <?php $count=1;
                                                                $total_amount=0;
                                                                $total_received=0;
                                                                ?>
                                                                @foreach($val as $row)
                                                                    <?php
                                                                    $data=SalesHelper::get_invoice_number_amount($row);
                                                                  echo   $total_amount+=$data->total_amount;
                                                                    $get_received=	SalesHelper::get_received_payment($row);
                                                                    $total_received+=$get_received->amount;

                                                                    ?>
                                                                    <input  type="text" class="form-control requiredField amount  invoice_amount" placeholder="Slip No" name="amount[]" onkeyup="manage_amount(this.id,'<?= $count?>');" id="amount{{$count}}" value="{{$data->total_amount-$get_received->amount}}" />
                                                                    <input type="hidden" name="hideAmt{{$count}}" id="hideAmt{{$count}}" value="{{$data->total_amount-$get_received->amount}}"/>
                                                                    <?php $count++; ?>
                                                                @endforeach

                                                                <?php

                                                                //$acc_id=CommonHelper::byers_name($buers_id);
                                                               //$acc_id=$acc_id->acc_id;
                                                                ?>
                                                                <input type="hidden" name="total_am" id="total_am" value="{{$total_amount}}"/>
                                                                <?php $total_amount=$total_amount-$total_received ?>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">RV Date.</label>
                                                                <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="rv_date_1" id="rv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Cheque No.</label>
                                                                <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                                <input type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="" />
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Cheque Date.</label>
                                                                <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="well">
                                                    <div class="panel">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="table-responsive">
                                                                        <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a></th>
                                                                                <th class="text-center" style="width:250px;">Recieved By.<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                                <th class="text-center" style="width:300px;">Description.<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                                <th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                                <th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                                <th class="text-center" style="width:120px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="addMoreRvsDetailRows_1" id="addMoreRvsDetailRows_1">
                                                                            <?php for($j = 1 ; $j <= 2 ; $j++){ ?>
                                                                            <input type="hidden" name="rvsDataSection_1[]" class="form-control requiredField" id="rvsDataSection_1" value="<?php echo $j?>" />
                                                                            <tr>
                                                                                <td>
                                                                                    <select class="form-control requiredField select2" name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>" @if($j==2){{ "disabled='true'" }} @endif >
                                                                                        <option value="0">Select Account</option>
                                                                                        @foreach($accounts as $key => $y)
                                                                                        <option value="{{ $y->id}}" @if($j==2 && $y->id==$client_accid->acc_id){{ "selected" }} @endif  >{{ $y->code .' ---- '. $y->name}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <?php $branchs = CommonHelper::get_all_branch();?>
                                                                                    <select class="form-control select2" name="branch_id_1_<?php echo $j ?>" id="branch_id_1_<?php echo $j ?>" disabled="true">
                                                                                        <option value=''>Select Recieved By</option>
                                                                                        @foreach($branchs as $row):
                                                                                        <option value='<?php echo $row->id; ?>' @if($row->id == $branch_id) {{ "selected" }} @endif> <?php echo $row->branch_name ?> </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <textarea class="form-control requiredField" name="desc_1_<?php echo $j ?>" id="desc_1_<?php echo $j ?>" required="required"/></textarea>
                                                                                </td>
                                                                                <td>
                                                                                    <input  @if($j==1) value="{{$total_amount}}" @else value="" @endif  onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1 amount" maxlength="15" min="0" type="text" name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1')"  />
                                                                                </td>
                                                                                <td>
                                                                                    <input @if($j==2) value="{{$total_amount}}" @else value="" @endif readonly placeholder="Credit" class="form-control c_amount_1 amount" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value=""/>
                                                                                </td>
                                                                                <td style="color: red" id="note{{$j}}" class="text-center">---</td>
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
                                                                                            class="form-control requiredField text-right"
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
                                                                                            class="form-control requiredField text-right"
                                                                                            value=""/>
                                                                                </td>
                                                                                <td class="diff" style="width:150px;font-size: 20px;"></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                        <input type="button" class="btn btn-sm btn-primary" onclick="addMoreRvsDetailRows('1')" value="Add More RV's Rows" />
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label class="sf-label">Description</label>
                                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                            <textarea name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rvsSection"></div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                            <input type="button" class="btn btn-sm btn-primary addMoreRvs" value="Add More RV's Section" />
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
        <script>
            $(document).ready(function() {
                $('.select2').select2();
                sum(1);
                $('.hidee').fadeOut();
                $('#total_amount').number(true,2);
                $('.amount').number(true,2);

                $('#d_amount_1_1').focus();
                $('#c_amount_1_2').focus();
                $('#amount1').focus();
                var r = 1;
                $('.addMoreRvs').click(function (e){
                    e.preventDefault();
                    r++;
                    var m = '<?php echo $_GET['m'];?>';
                    $.ajax({
                        url: '<?php echo url('/')?>/fmfal/makeFormBankReceiptVoucher',
                        type: "GET",
                        data: { id:r,m:m},
                        success:function(data) {
                            $('.rvsSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bankRvs_'+r+'"><a href="#" onclick="removeRvsSection('+r+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                        }
                    });
                });

                $(".btn-success").click(function(e){
                    $('select').prop('disabled', false);
                    var rvs = new Array();
                    var val;
                    $("input[name='rvsSection[]']").each(function(){
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
            });
            var total=0;
            var x = 2;
            function addMoreRvsDetailRows(id){

                x++;
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/addMoreBankRvsDetailRows',
                    type: "GET",
                    data: { counter:x,id:id,m:m},
                    success:function(data) {
                        $('.addMoreRvsDetailRows_'+id+'').append(data);
                        $('#account_id_1_'+x+'').select2();
                        $('#branch_id_1_'+x+'').select2();
                        $('#account_id_1_'+x+'').focus();
                        credit_amount_pass();
                    }
                });
            }

            function removeRvsRows(id,counter){
                var elem = document.getElementById('removeRvsRows_'+id+'_'+counter+'');
                elem.parentNode.removeChild(elem);
                x--;
            }
            function removeRvsSection(id){
                var elem = document.getElementById('bankRvs_'+id+'');
                elem.parentNode.removeChild(elem);
            }

            $('#indent').change(function()
            {

                if($(this).is(':checked'))
                {
                    $('.hidee').fadeIn(1000);

                }
                else
                {

                    $('.hidee').fadeOut();
                    $('#d_amount_1_1').val('');
                    $('#d_amount_1_1').focus();
                    $('#d_amount_1_2').val('');
                    $('#d_amount_1_2').focus();
                    $('#note1').text('');
                    $('#note2').text('');

                    for (i=3; i<=x; i++)
                    {
                        removeRvsRows(1,i);
                    }

                }

            });
        </script>


        <script type="text/javascript">

            $('.select2').select2();

            function inden_commision()
            {
                var currency_rate=parseFloat($('#currency_rate').val());
                var exchange_rate=parseFloat($('#exchange_rate').val());
                total=currency_rate * exchange_rate;
                $('#total_amount').val(total);
                var withholding=(total /100) * 5;
                var bank_amount=total-withholding;
                $('#d_amount_1_1').val(bank_amount);
                $('#d_amount_1_1').focus();
                $('#d_amount_1_2').val(withholding);
                $('#d_amount_1_2').focus();
                $('#note1').text('For Bank Amount');
                $('#note2').text('For Withholding');
                addMoreRvsDetailRows(1);



            }

            function credit_amount_pass()
            {
                if($('#indent').is(':checked') &&  x==3)
                {
                    $('#c_amount_1_3').focus();
                    $('#c_amount_1_3').val(total);
                    sum(1);
                }
            }

            function manage_amount(id,count)
            {
             var    amount = parseFloat($('#amount'+count).val());
               var  hide_amount = parseFloat($('#hideAmt'+count).val());
             var    total_am = $('#total_am').val();
                var sum_amount=0;
                if(hide_amount >= amount)
                {

                }
                else {
                    $('#amount'+count).val(hide_amount);
                }
                $('.invoice_amount').each(function(i, obj) {
                    sum_amount+=+parseFloat($('#'+obj.id).val());
                });
                $('#d_amount_1_1').val(sum_amount);
                $('#c_amount_1_2').val(sum_amount);
                sum(1);
            }

        </script>
        <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection