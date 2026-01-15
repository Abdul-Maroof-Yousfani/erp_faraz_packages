<?php

use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$sub_department_input = '';
foreach($departments as $key => $y){
    $sub_department_input .= '<optgroup label="'.$y->department_name.'" value="'.$y->id.'">';
    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` ='.$y->id.'');
    foreach($subdepartments as $key2 => $y2){
        $sub_department_input .= '<option value="'.$y2->id.'">'.$y2->sub_department_name.'</option>';
    }
        $sub_department_input .= '</optgroup>';

}
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
                        <span class="subHeadingLabelClass">Edit Journal Voucher Form</span>
                    </div>




                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => '/UpdateJv?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php //echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php //echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                    </div>

                                </div>

                                <div class="row">

                                    <input type="hidden" name="type" id="type" value="1" />
                                    <input  checked  type="hidden" value="1" name="payment_mod"  />
                                    <input  checked  type="hidden" value="{{$id}}" name="id"  />
                                    <input  checked  type="hidden" value="{{$NewJv->jv_no}}" name="jv_no" />

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">JV No</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
                                                name="" id="" value="{{ strtoupper($NewJv->jv_no) }}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">JV Date.</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="" name="jv_date_1" id="jv_date_1" value="<?php echo $NewJv->jv_date; ?>" />
                                    </div>

                                </div>


                                <div class="lineHeight">&nbsp;</div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="buildyourform" class="table table-bordered">
                                                <thead>
                                                <tr class="text-center">
                                                    <th colspan="1" class="text-center">Journal Voucher Detail</th>
                                                    <th colspan="2" class="text-center"><input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" /></th>
                                                    <th class="text-center"><span class="badge badge-success" id="span"><?php echo $NewJvData->count();?></span></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                                                    </th>
                                                    <th class="text-center hide" style="width:150px;">Cost Center</th>
                                                    <th class="text-center hide" style="width:350px;">Description <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" style="width:150px;">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                                <?php $j = 0 ; $debit=0; $credit=0;

                                                foreach($NewJvData as $val){ $j++;
                                                if($val->debit_credit==1): $debit += $val->amount; elseif($val->debit_credit==0): $credit += $val->amount; endif;
                                                ?>
                                                <input type="hidden" name="rvsDataSection_1[]" class="form-control" id="rvsDataSection_1" value="<?php echo $j?>" />
                                                <tr id="tr<?php echo $j?>" class="AutoNo">
                                                    <td>
                                                        <?php $Acc = CommonHelper::get_single_row('accounts','id',$val->acc_id);?>
                                                            <span id="AccLoader<?php echo $j?>"></span>
                                                        <select style="width: 100%" class="form-control requiredField select2" name="account_id[]" id="account_id{{$j}}" onchange="AppendBrand('<?php echo $j?>');Datavalidate(this)">
                                                            <option value="0,0">Select Account</option>
                                                            <option value="{{$Acc->id.','.$Acc->type.','.$Acc->code}}"  selected  >{{ $Acc->code .' ---- '. $Acc->name}}</option>
                                                        </select>
                                                            <br>
                                                            <br>
                                                            <button type="button" class="btn btn-xs btn-info" onclick="getAccount('<?php echo $j?>','0')">All</button>
                                                            <button type="button" class="btn btn-xs btn-primary" onclick="getAccount('<?php echo $j?>','<?php echo $val->acc_id?>')">By Default</button>
                                                    </td>

                                                    <td class="hide">
                                                        <select class="form-control select2 sub_department_id" name="sub_department_id[]" id="sub_department_id_{{$j}}">
                                                            <option value="">Select Department</option>
                                                            @foreach($departments as $key => $y)
                                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                    <?php
                                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` ='.$y->id.'');
                                                                    ?>
                                                                    @foreach($subdepartments as $key2 => $y2)
                                                                        <option {{ $y2->id == optional($val)->sub_department_id ? 'selected' : '' }} value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td class="hide">
                                                        <textarea class="form-control" name="desc[]" id="desc_1_{{$j}}"><?php echo $val->description;?></textarea>
                                                    </td>

                                                    <td>
                                                        <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField number_format" maxlength="15" min="0" type="text" name="d_amount[]" id="d_amount_1_{{$j}}" onkeyup="sum('1')" value="{{ $val->debit_credit==1? $val->amount:'0' }}" required="required"/>
                                                    </td>
                                                    <td>
                                                        <input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField number_format" maxlength="15" min="0" type="text" name="c_amount[]" id="c_amount_1_{{$j}}" onkeyup="sum('1')" value="{{ $val->debit_credit==0? $val->amount:'0' }}" required="required"/>
                                                    </td>
                                                    <?php if($j > 2):?>
                                                    <td class='text-center'> <input type='button' onclick='RemoveRow("<?php echo $j?>")' value='Remove' class='btn btn-sm btn-danger'> </td>
                                                    <?php else:?>
                                                    <td class='text-center'>---</td>
                                                    <?php endif;?>
                                                </tr>
                                                <?php }       ?>
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
                                                                value="{{$debit}}"/>
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
                                                                value="{{$credit}}"/>
                                                    </td>
                                                    <td class="diff" style="width:150px;font-size: 20px;">
                                                        <input readonly style="color: blue;font-weight: 600" class="form-control" type="text" id="diff" value="{{$debit-$credit}}"/>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Description</label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"><?= $NewJv->description ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pvsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <!--
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

										<input type="button" class="btn btn-sm btn-primary addMorePvs" value="Add More PV's Section" />
										<!-->
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
            </div>
        </div>
    </div>

<?php  ?>
    <script>


        $(document).ready(function(){
            $('.select2').select2();
          //  $('.number_format').number(true,2);
        });

        function AppendBrand(Row)
        {
            var AccountAndType = $('#account_id'+Row).val();
            AccountAndType = AccountAndType.split(",");

            if(AccountAndType[0] != "0")
            {
                $('#Loader' + Row).html('<img src="/assets/img/loading.gif" alt="">');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/getBranchClientWise',
                    type: "GET",
                    data: {AccountAndType:AccountAndType},
                    success: function (data) {
                        $('#paid_to'+Row).html('');
                        $('#paid_to'+Row).html(data);
                        $('#Loader' + Row).html('');
                    }
                });
            }
            else
            {
                $('#paid_to'+Row).html('');
            }
        }

        function getAccount(Count,Id)
        {

            if(Id != 0)
            {
                $('#AccLoader' + Count).html('<img src="/assets/img/loading.gif" alt="">');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/getAccount',
                    type: "GET",
                    data: {Id:Id},
                    success: function (data) {
                        $('#paid_to'+Count).html('');
                        $('#AccLoader' + Count).html('');
                        $('#account_id'+Count ).html(data);

                        var AccountAndType = $('#account_id'+Count).val();
                        var PaidToAndType = $('#paid_to_hidden'+Count).val();
                        AccountAndType = AccountAndType.split(",");
                        alert(AccountAndType);
                        $('#Loader' + Count).html('<img src="/assets/img/loading.gif" alt="">');
                        $.ajax({
                            url: '<?php echo url('/')?>/fmfal/getBranchClientWiseSingle',
                            type: "GET",
                            data: {AccountAndType:AccountAndType,PaidToAndType:PaidToAndType},
                            success: function (data) {
                                $('#paid_to'+Count).html('');
                                $('#paid_to'+Count).html(data);
                                $('#Loader' + Count).html('');
                            }
                        });
                    }
                });


            }
            else
            {

                $('#AccLoader' + Count).html('<img src="/assets/img/loading.gif" alt="">');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/getAccount',
                    type: "GET",
                    data: {Id:Id},
                    success: function (data) {
                        $('#paid_to'+Count).html('');
                        $('#AccLoader' + Count).html('');
                        $('#account_id'+Count ).html(data);
                    }
                });
            }

        }
    </script>

    <script>
        var x = '<?= $j ?>';
        var x2 = 1;
        function AddMorePvs()
        {
            x++;
            $('#addMorePvsDetailRows_1').append("<tr class='AutoNo' id='tr"+x+"' >"+
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+x+"' onchange='AppendBrand("+x+");Datavalidate(this)'><option value='0,0'>Select Account</option><?php foreach(CommonHelper::get_all_account_operat() as $Fil){?><option value='<?php echo $Fil->id.','.$Fil->type.','. $Fil->code?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "<td class="hide">"+
                    "<select class='form-control select2 sub_department_id' name='sub_department_id[]' id='sub_department_id_"+x+"'><option value=''>Select Department</option>"+
                        '{!! $sub_department_input !!}'+
                    "</select>"+
                    "</td>"+
                    "<td class="hide">"+
                    '<textarea class="form-control requiredField" name="desc[]" id="desc_1_'+x+'"/> </textarea>'+
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


        function remove(cls)
        {
            $('.'+cls).remove();
        }
    </script>
    <script !src="">


    </script>


    <script>
        $(".btn-success").click(function(e){
            CheckDebitCredit();
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

        function RemoveRow(x)
        {
            $('#tr'+x).remove();
            sum(1);
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection
