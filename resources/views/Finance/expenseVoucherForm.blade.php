<?php
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <script>
        function	check_uncheck()
        {
            if ($("#first_level_chk").is(":checked"))
            {
                $('#account_id').fadeOut();
            }

            else
            {
                $('#account_id').fadeIn();
            }
        }
    </script>
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
                                    <span class="subHeadingLabelClass">Add Chart of Account</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'fad/addExpenseVoucherDetail?m='.$m.'','id'=>'chartofaccountForm'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <?php $EvNo = CommonHelper::get_unique_ev_no();?>
                                                    <label>Voucher No:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" id="VoucherNo" name="VoucherNo" class="form-control requiredField" readonly value="<?php echo strtoupper($EvNo);?>">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Voucher Date:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" id="VoucherDate" name="VoucherDate" class="form-control requiredField" value="<?php echo date('Y-m-d')?>">
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Debit Account Head:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select name="DebitAccId" id="DebitAccId" class="form-control select2 requiredField">
                                                        <option value="">Select Account Head</option>
                                                        <?php foreach($Accounts as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Credit Account Head:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select name="CreditAccId" id="CreditAccId" class="form-control select2 requiredField">
                                                        <option value="">Select Account Head</option>
                                                        <?php foreach($Accounts as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label for="">Description</label>
                                                    <textarea name="Desc" id="Desc" cols="30" rows="4" placeholder="Description" class="form-control"></textarea>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                                                <div class="">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table id="buildyourform" class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Sr No<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    <th class="text-center">So No<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    <th class="text-center">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    <th class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-primary" id="BtnAddMore" onclick="AddMoreRows()">Add More</button>
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="AppendHtml">
                                                                    <tr class="text-center">
                                                                        <td>1</td>
                                                                        <td style="width: 250px;">
                                                                            <select name="SoNo[]" id="SoNo1" class="form-control select2 requiredField">
                                                                                <option value="">Select So No</option>
                                                                                <?php foreach($SoNo as $Fil):?>
                                                                                <option value="<?php echo $Fil->so_no?>"><?php echo $Fil->so_no?></option>
                                                                                <?php endforeach;?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" class="form-control text-right requiredField GetAmount" id="Amount1" name="Amount[]" placeholder="Amount" step="any" onkeyup="Calculation(1)">
                                                                        </td>
                                                                        <td> - - - </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="2"><strong style="font-size: 20px">TOTAL</strong></td>
                                                                        <td class="text-right"><strong style="font-size: 20px" id="TotalAmount"></strong></td>
                                                                        <td style="background-color: darkgray"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                </div>
                                                <?php
                                                echo Form::close();
                                                ?>
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
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $(".btn-success").click(function(e)
            {

                var demands = new Array();
                var val;
                //	$("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());

                //});
                var _token = $("input[name='_token']").val();

                for (val of demands)
                {

                    jqueryValidationCustom();
                    if(validate == 0){


                    }else{
                        return false;
                    }
                }

            });
        });


        var Counter = 1;

        function AddMoreRows()
        {
            Counter++;
            $('#AppendHtml').append('<tr class="text-center" id="RemoveTr'+Counter+'">' +
                    '<td class="AutoCounter">'+Counter+'</td>' +
                    '<td style="width: 250px;">' +
                    '<select name="SoNo[]" id="SoNo'+Counter+'" class="form-control select2 requiredField">' +
                    '<option value="">Select So No</option>'+
                    <?php foreach($SoNo as $Fil):?>
                    '<option value="<?php echo $Fil->so_no?>"><?php echo $Fil->so_no?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control text-right requiredField GetAmount" id="Amount'+Counter+'" name="Amount[]" placeholder="Amount" step="any" onkeyup="Calculation('+Counter+')">' +
                    '</td>' +
                    '<td><button type="button" class="btn btn-xs btn-danger" onclick="RemoveSection('+Counter+')" id="RemoveRows'+Counter+'">Remove</button></td>' +
                    '</tr>');
            $('#SoNo'+Counter).select2();
            var AutoCount = 1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).html(AutoCount);
            });
        }

        function RemoveSection(Row) {
//            alert(Row);
            $('#RemoveTr' + Row).remove();
            $(".AutoCounter").html('');
            var AutoCount = 1;
            $(".AutoCounter").each(function () {
                AutoCount++;
                $(this).html(AutoCount);
            });
        }

        function Calculation(Row)
        {

            var TotalAmount = 0;
            $(".GetAmount").each(function(){
                TotalAmount += parseFloat($(this).val());
            });
            $('#TotalAmount').html(TotalAmount.toFixed(2));

        }
    </script>

@endsection