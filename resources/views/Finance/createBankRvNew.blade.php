<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$brv_no=CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
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
                        <div class="headquidd">
                            <span class="subHeadingLabelClass">Create Bank Recipt Voucher Form</span>
                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => '/insertBankRv?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">

                                    <input type="hidden" name="type" id="type" value="1" />
                                    <input  checked  type="hidden" class="" value="1" name="payment_mod"  />

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">RV No</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
                                                name="" id="" value="{{strtoupper($brv_no)}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">RV Date.</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="rv_date_1" id="jv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <label class="sf-label">Ref / Bill No.</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="text" class="form-control" max="<?php echo date('Y-m-d') ?>" name="ref_bill_no_1" id="ref_bill_no_1" placeholder="Ref / Bill No" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Cheque No</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="text" class="form-control requiredField"  name="cheque_no_1" id="cheque_no_1" placeholder="Cheque No" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Cheque Date.</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="" name="cheque_date_1" id="cheque_date_1" value="" />
                                    </div>

                                </div>


                                <div class="lineHeight">&nbsp;</div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class='jhed headquid'>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="subHeadingLabelClass">Bank Receipt Voucher Detail</span>
                                                </div>
                                        
                                                <div class="col-md-6 text-right">
                                                    <input  type="button" class="btn btn-sm btn-primary" onclick="AddMoreRvs()" value="Add More RV's Rows" />
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="table-responsive">
                                            <table id="buildyourform" class="userlittab table table-bordered sf-table-list">
                                                <thead>
                                                <tr>
                                                    <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                                                    </th>
                                                    </th><th class="text-center hide" style="width:250px;">Received By<span class="rflabelsteric"><strong>*</strong></span></th>
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


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label class="sf-label">Description</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pvsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <div class="headquid"> {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}</div>
                           
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            $('.select2').select2();
            $('.number_format').number(true,2);
        });
    </script>

    <script>
        var x = 2;
        var x2=1;
        function AddMoreRvs()
        {
            x++;

            $('#addMorePvsDetailRows_1').append("<tr id='tr"+x+"' class='AutoNo'>"+
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+x+"'><option value=''>Select Account</option><?php foreach(CommonHelper::get_all_account_operat() as $Fil){?><option value='<?php echo $Fil->id.',0'?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "<td class='hide'>"+
                    "<select class='form-control  select2' name='paid_to[]' id='paid_to"+x+"'><option value='0,0'>Select Paid To</option>"+
                    <?php foreach($MultiData['Emp'] as $EmpFil):?>
                    "<option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>"+
                    <?php endforeach;?>
                    <?php foreach($MultiData['Supp'] as $SuppFil):?>
                    "<option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>"+
                    <?php endforeach;?>
                    <?php foreach($MultiData['Client'] as $ClientFil):?>
                    "<option value='<?php echo $ClientFil->id.","."3";?>'><?php echo $ClientFil->client_name?></option>"+
                    <?php endforeach;?>
                    <?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
                    "<option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>"+
                    <?php endforeach;?>
                     "</select>"+
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
            $('.number_format').number(true,2);
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
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection
