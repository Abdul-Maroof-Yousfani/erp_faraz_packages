<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$jv_no=CommonHelper::uniqe_no_for_jv(date('y'),date('m'));
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
                       <div class='headquidd'>
                       <span class="subHeadingLabelClass">Create Journal Voucher Form</span>
                       </div>
                    </div>
                </div>


                <div class="row">
                    <?php echo Form::open(array('url' => '/insertJournalVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>

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
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                    </div>

                                </div>

                                <div class="row">

                                    <input type="hidden" name="type" id="type" value="1" />
                                    <input  checked  type="hidden" class="" value="1" name="payment_mod"  />

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">JV No</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
                                                name="" id="" value="{{strtoupper($jv_no)}}" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">JV Date.</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="" name="jv_date_1" id="jv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 hide">
                                        <label class="sf-label">JV Day.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control"  name="jv_day" id="jv_day"  />
                                    </div>
                                </div>


                                <div class="lineHeight">&nbsp;</div>

                                <div class='jhed headquid'>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="subHeadingLabelClass">Journal Voucher Detail</span>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <input type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" />
                                            <span class="badge badge-success" id="span">2</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">

                                            <table id="buildyourform" class="userlittab table table-bordered sf-table-list">
                                                <thead>

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
                                                <?php for($j = 1 ; $j <= 2 ; $j++){?>
                                                <input type="hidden" name="rvsDataSection_1[]" class="form-control" id="rvsDataSection_1" value="<?php echo $j?>" />
                                                <tr class="AutoNo">
                                                    <td>
                                                        <select style="width: 100%" class="form-control select2 requiredField" name="account_id[]" id="account_id{{ $j }}" onchange="AppendBrand('<?php echo $j?>');Datavalidate(this)">
                                                            <option value="">Select Account</option>
                                                            @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                                                                <option value="{{ $y->id.','.$y->type.','.$y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="hide">
                                                        <select class="form-control select2" name="sub_department_id[]" id="">
                                                            <option value="">Select Department</option>
                                                            @foreach($departments as $key => $y)
                                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                    <?php
                                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` ='.$y->id.'');
                                                                    ?>
                                                                    @foreach($subdepartments as $key2 => $y2)
                                                                        <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="hide">
                                                        <textarea class="form-control" name="desc[]" id="desc_1_{{$j}}"/></textarea>
                                                    </td>
                                                    <td>
                                                        <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField" maxlength="15" min="0" type="text" name="d_amount[]" id="d_amount_1_{{$j}}" onkeyup="sum('1')" value="" required="required"/>
                                                    </td>
                                                    <td>
                                                        <input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField" maxlength="15" min="0" type="text" name="c_amount[]" id="c_amount_1_{{$j}}" onkeyup="sum('1')" value="" required="required"/>
                                                    </td>
                                                    <td class="text-center">---</td>
                                                </tr>

                                                <script>
                                                    $('#account_id{{ $j }}').select2();
                                                </script>
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
        $(document).ready(function(){
            $('.select2').select2();
            $('.number_format').number(true,2);
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

    </script>




    <script>
        var x = 2;
        var x2=1;
        function AddMorePvs()
        {
            x++;

            $('#addMorePvsDetailRows_1').append("<tr class='AutoNo' id='tr"+x+"' >"+
                    "<td>"+
                    "<select style='width: 100%' class='form-control requiredField select2' name='account_id[]' id='account_id"+x+"' onchange='AppendBrand("+x+");Datavalidate(this)'><option value='0,0'>Select Account</option><?php foreach(CommonHelper::get_all_account_operat() as $Fil){?><option value='<?php echo $Fil->id.','.$Fil->type.','.$Fil->code?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "<td class='hide'>"+
                    "<select class='form-control select2 sub_department_id' name='sub_department_id[]' id='"+x+"'><option value=''>Select Department</option>"+
                        '{!! $sub_department_input !!}'+
                    "</select>"+
                    "</td>"+
                    "<td class='hide'>"+
                    '<textarea class="form-control" name="desc[]" id="desc_1_'+x+'"/> </textarea>'+
                    "</td>"+
                    "<td>"+
                    '<input  placeholder="Debit" class="form-control d_amount_'+x2+' requiredField" onfocus="mainDisable('+$.trim("'c_amount_1_"+x+"','d_amount_1_"+x+"'")+')" maxlength="15" min="0" type="any" name="d_amount[]" id="d_amount_1_'+x+'" onkeyup="sum('+$.trim("'"+x2+"'")+')" value="" required="required"/>'+
                    "</td>"+
                    "<td>"+
                    '<input  placeholder="Credit" class="form-control c_amount_'+x2+' requiredField" onfocus="mainDisable('+$.trim("'d_amount_1_"+x+"','c_amount_1_"+x+"'")+')" maxlength="15" min="0" type="any" name="c_amount[]" id="c_amount_1_'+x+'" onkeyup="sum('+$.trim("'"+x2+"'")+')" value="" required="required"/>'+
                    "</td>"+
                    "<td class='text-center'> <input type='button' onclick='RemoveRow("+x+")' value='Remove' class='btn btn-sm btn-danger'> </td></tr>");
            $('.select2').select2();
        //    $('.number_format').number(true,2);
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
        function Datavalidate(id)
        {
            var data  =  $(id).find(':selected').val();
            console.log(data);
            data =  data.split(',');
            var data = data[2]
            var closestContainer = $(id).closest('.AutoNo').find('.sub_department_id'); // Find the nearest element with the class "container"
            if(data[0] ==  4 || data[0] == 5)
            {
              //  closestContainer.addClass('requiredField');
            }else{
              //  closestContainer.removeClass('requiredField');
            }
        }

    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection
