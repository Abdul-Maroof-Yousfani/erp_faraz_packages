<?php


$str = DB::Connection('mysql2')->selectOne("select count(id)id from jvs where status=1 and jv_date='".date('Y-m-d')."'

			")->id;
$pv_no = 'jv'.($str+1);
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Models\BreakupData;



?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Journal Voucher</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'fad/addJournalVoucherDetail?m='.$m.'','id'=>'cashPaymentVoucherForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="jvsSection[]" class="form-control requiredField" id="jvsSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" style="padding: 0px 15px; font-size: 20px;"><strong>Referencing</strong></div>
                                                    <div class="panel-body" style="border: solid 1px #ddd;">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <label class="sf-label">Vendor  <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <select onchange="" class="form-control select2 supplier_val" style="font-size: 13px;" name="supplier_id"  id="supplier_id">
                                                                <option value="">Select</option>
                                                                @foreach(CommonHelper::get_all_supplier() as $row)
                                                                    <option value="{{$row->id.','.$row->acc_id}}">{{$row->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="adv_amount" id="adv_amount"/>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <label class="sf-label">Advance / Opening  <span class="rflabelsteric"><strong>*</strong></span></label>

                                                            <select onchange="get_ledger_refrence_wise()" class="form-control" style="font-size: 13px" name="breakup_id"  id="breakup_id">
                                                                <option value="0">Select</option>

                                                            </select>
                                                            <input type="hidden" name="adv_amount" id="adv_amount"/>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                            <label class="sf-label">New Refrence  <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <input style="display: none" class="form-control"  type="text" name="new_ref" id="new_ref" value=""/>

                                                            </div>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                               <span id="breakup_data"></span>

                                            </div>

                                        </div></div></div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">JV No</label>
                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
                                                    name="" id="" value="{{strtoupper($pv_no)}}" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">JV Date.</label>
                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input autofocus onblur="change_day()" onchange="change_day()"  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="jv_date_1" id="pv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">JV  Day.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2  col-xs-12">
                                            <label class="sf-label">Ref / Bill No.</label>
                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input   type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_1" id="slip_no_1" value="" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2  col-xs-12">
                                            <label class="sf-label">Bill Date</label>
                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input autofocus  type="date" class="form-control requiredField" name="bill_date" id="bill_date" value="{{date('Y-m-d')}}" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2  col-xs-12">
                                            <label class="sf-label">Due Date</label>
                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                            <input autofocus  type="date" class="form-control requiredField" name="due_date" id="due_date" value="{{date('Y-m-d')}}" />
                                        </div>



                                    </div>
                                </div>



                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="color: red" id="percent_cal1" class=""> </p>
                                </div>


                            </div>

                            <div class="checkbox">
                                <label><input onclick="show()"  name="items" type="checkbox" id="items" value="1">With Items</label>
                            </div>






                            <div class="lineHeight">&nbsp;</div>


                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                            <thead>
                                            <tr>
                                                <th  class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>
                                                <th class="text-center" style="width:150px;">Current Bal<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                <th style="width: 200px;" class="text-center hidden-print hidee"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
                                                <th style="width: 100px" class="text-center hidee">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 150px;" class="text-center hidee">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th style="width: 150px;" class="text-center hidee">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>

                                                <th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                <th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                <th style="width: 50px;" class="text-center" style="width:150px;">Allocation<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                            </tr>
                                            </thead>
                                            <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                            <?php $tab_index=6; for($j = 1 ; $j <= 2 ; $j++){?>
                                            <input type="hidden" name="jvsDataSection_1[]" class="form-control requiredField" id="jvsDataSection_1" value="<?php echo $j?>" />
                                            <tr @if($j==2) class="last" @endif>
                                                <td>
                                                    <select   onchange="get_current_amount(this.id);" style="width: 100%" class="form-control requiredField select2 accounts AccountsAppend" name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>">
                                                        <option value="">Select Account</option>
                                                        @foreach(CommonHelper::get_accounts_for_jvs() as $key => $y)
                                                            <option value="{{ $y->id.'~'.$y->supp_id.'~'.$y->supplier_id}}">{{$y->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <input type="hidden" id="current_amount_hidden<?php echo $j ?>"/>
                                                <script>
                                                    //get_current_amount('< ?php echo $y->id ?>');
                                                </script>
                                                <td>
                                                    <input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $j ?>"  value="" required="required"/>
                                                </td>


                                                <td class="hidee">
                                                    <select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_<?php echo $j ?>" id="sub_item_id_1_<?php echo $j ?>" class="form-control select2">
                                                        <option value="">Select</option>

                                                        @foreach(CommonHelper::get_all_subitem() as $row)
                                                            <option value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="hidee">
                                                    <input readonly type="text" name="uom_1_<?php echo $j ?>" id="uom_1_<?php echo $j ?>" class="form-control" />
                                                    <input type="hidden" name="uom_id_1_<?php echo $j ?>" id="uom_id_1_<?php echo $j ?>" class="form-control" />
                                                </td>

                                                <td class="hidee">
                                                    <input onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $j ?>','<?php echo $j ?>')"  type="number" step="0.01" name="qty_1_<?php echo $j ?>" id="qty_1_<?php echo $j ?>" class="form-control qty" />
                                                </td>

                                                <td class="hidee">
                                                    <input  onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $j ?>','<?php echo $j ?>')" type="text" step="0.01" name="rate_1_<?php echo $j ?>" id="rate_1_<?php echo $j ?>" class="form-control rate" />
                                                </td>







                                                <td>
                                                    <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');"
                                                           placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text"
                                                           name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'1');calc_amount(this.id);dept_cost_amount(this.id,'<?php echo $j ?>')" value="" required="required"/>
                                                </td>

                                                <td>
                                                    <input   onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control requiredField c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'0');dept_cost_amount(this.id,'<?php echo $j ?>')" value="" required="required"/>
                                                </td>
                                                <td class="text-center"><input onclick="checked_unchekd({{$j}},'0')" type="checkbox" id="allocation<?php echo $j ?>" name="allocation<?php echo $j ?>"/> </td>
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
                                                            type="text"
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
                                                            type="text"
                                                            readonly="readonly"
                                                            id="c_t_amount_1"
                                                            maxlength="15"
                                                            min="0"
                                                            name="c_t_amount_1"
                                                            class="form-control requiredField text-right"
                                                            value=""/>
                                                </td>
                                                <td class="diff" style="width:150px;font-size: 20px;">
                                                    <input readonly style="color: blue;font-weight: 600" class="form-control" type="text" id="diff" value=""/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="12" style="font-size: 20px;color: navy;" id="rupees"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        @for($x=1; $x<=2; $x++)

                                            <div id="" class="row">

                                                <p style="color: #e2a0a0;text-align: center" id="paragraph{{$x}}"> </p>
                                                @include('Finance.dept_allocation_finance')
                                                @include('Finance.cost_center_allocation_finance')
                                            </div>
                                        @endfor
                                        <div id="cost_data3" class="row">


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <input  type="button" class="btn btn-sm btn-primary" onclick="addMorePvsDetailRows('1')" value="Add More JV's Rows" />
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Description</label>
                                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                        <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  ?>
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



    <?php

    ?>
    <script>

        var SelectVal=[];
        var Selecttxt=[];
        var ajaxformdept=0;

        var SelectValCostCenter=[];
        var SelecttxtCostCenter=[];
        var ajaxformdeptCostCenter=0;
        $(document).ready(function() {
            $('.hidee').fadeOut();

            var d = new Date();

            var weekday = new Array(7);
            weekday[0] = "Sunday";
            weekday[1] = "Monday";
            weekday[2] = "Tuesday";
            weekday[3] = "Wednesday";
            weekday[4] = "Thursday";
            weekday[5] = "Friday";
            weekday[6] = "Saturday";

            var n = weekday[d.getDay()];

            document.getElementById("pv_day").value = n;


            $('.taxes').fadeOut(500);
            $('#d_t_amount_1').number(true,2);
            $('#diff').number(true,2);
            $('#c_t_amount_1').number(true,2);
            for(i=1; i<=2; i++)
            {
                $('#d_amount_1_'+i).number(true,2);
                $('#c_amount_1_'+i).number(true,2);
                $('#current_amount'+i).number(true,2);
                $('#rate_1_'+i).number(true,2);

                $('#cost_center_department_amount_'+i+'_1').number(true,2);
                $('#department_amount_'+i+'_1').number(true,2);
                $('#total_dept'+i).number(true,2);
                $('#cost_center_total_dept'+i).number(true,2);
            }
            var number = 12345;


            var p = 1;
            $('.addMorePvs').click(function (e){
                e.preventDefault();
                p++;
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/makeFormBankPaymentVoucher',
                    type: "GET",
                    data: { id:p,m:m},
                    success:function(data) {
                        $('.pvsSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bankPvs_'+p+'"><a href="#" onclick="removePvsSection('+p+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                    }
                });
            });

            $(".btn-success").click(function(e){
                var pvs = new Array();
                var val;
                $("input[name='jvsSection[]']").each(function(){
                    pvs.push($(this).val());
                });
                var _token = $("input[name='_token']").val();

                var debit=$('#d_t_amount_1').val();
                var credit=$('#c_t_amount_1').val();


                if (debit!=credit)
                {
                    alert('DEBIT & CREDIT NOt EQUAL');
                    return false;
                }

                var auth=dept_amount_validation();


                var auth2=cost_center_amount_validation();



                for (val of pvs) {


                    if (auth==1 && auth2==1)
                    {
                        jqueryValidationCustom();


                    }
                    else

                    {

                        return false;
                    }
                    if(validate == 0)
                    {
                        $('#account_id_1_2').attr('disabled', false);
                    }
                    else
                    {
                        return false;
                    }
                }

            });
        });
        var x ='<?php echo $j-1 ?>';
        function addMorePvsDetailRows(id){
            x++;
            var items=0;
            if ($('#items').is(':checked'))
            {
                items=1;
            }
            else
            {
                items=0;
            }
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/fmfal/addJournalVoucherDetailRows_costing',
                type: "GET",
                data: { counter:x,id:id,m:m,items:items},
                success:function(data)
                {



                    var response=data.split('*');
                    //   alert(response[1]);
                    var vendor=$('#supplier_id').val();

                    if (vendor!='')
                    {
                        $('.addMorePvsDetailRows_'+id+'').append(response[0]).after($( ".last" ));
                    }
                    else
                    {
                        $('.addMorePvsDetailRows_'+id+'').append(response[0]);
                    }



                    $('#cost_data'+x).append(response[1]);
                    $('#d_amount_1_'+x).number(true,2);
                    //    $('#sub_item_id_1_'+x).select2();
                    $('#department_'+x+'_1').select2();
                    $('#cost_center_department_'+x+'_1').select2();
                    $('#c_amount_1_'+x).number(true,2);
                    $('#current_amount'+x).number(true,2);
                    $('#account_id_1_'+x+'').select2();
                    $('#sub_item_id_1_'+x+'').select2();
                    $('#account_id_1_'+x+'').focus();

                    $('#cost_center_department_amount_'+x+'_1').number(true,2);
                    $('#department_amount_'+x+'_1').number(true,2);
                    $('#cost_center_total_dept'+x).number(true,2);
                    $('#total_dept'+x).number(true,2);

                    //	$("#LeNomDeMaBaliseID").prop('id', 'LeNouveauNomDeMaBaliseID');

                }
            });
        }

        function removePvsRows(id,counter)
        {

            var elem = document.getElementById('removePvsRows_'+id+'_'+counter+'');
            elem.parentNode.removeChild(elem);

            var elem = document.getElementById('remove_'+counter);
            elem.parentNode.removeChild(elem);



        }
        function removePvsSection(id)
        {
            var elem = document.getElementById('bankPvs_'+id+'');
            elem.parentNode.removeChild(elem);

        }
    </script>
    <script type="text/javascript">

        $('.select2').select2();
        //	$("#account_id_1_1").prop('tabindex',6);
        //$("#account_id_1_2").prop('tabindex',9);

    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <script>
        $(document).keydown(function(evt){
            if (evt.keyCode==77 && (evt.ctrlKey)){
                evt.preventDefault();
                addMorePvsDetailRows(1);
            }


        });


    </script>








    <script type="text/javascript">
        $(document).ready(function(){
            $('input[name="adv"]').click(function(){
                if($(this).is(":checked")){
                    $('#type').val(2);
                }
                else if($(this).is(":not(:checked)")){
                    $('#type').val(1);
                }
            });
        });
        function get_detail_purchase_voucher(id) {



            var number=id.replace("sub_item_id_", "");
            number=number.split('_');
            number=number[1];

            // for finance department
            var dept_name = $('#' + id + ' :selected').text();
            $('#dept_item'+number).text(number+'-'+' '+dept_name);
            $('#cost_center_dept_item'+number).text(number+'-'+' '+dept_name);

            // End
            id=$('#'+id).val();
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/get_detail_purchase_voucher',
                type: "GET",
                data: { id:id},
                success:function(data)
                {

                    data=data.split('*');

                    $('#uom_1_'+number).val(data[0]);
                    $('#rate_1_'+number).val(data[1]);
                    $('#uom_id_1_'+number).val(data[2]);
                }
            });
        }
    </script>

    <script>
        function calculation_amount(id)
        {
            var  number= id.split('_');
            number=number[2];

            var rate= $('#rate_1_'+number).val();
            if (isNaN(rate)==true)
            {
                rate=0;
            }
            var qty=$('#'+'qty_1_'+number).val();
            if (isNaN(qty)==true)
            {
                qty=0;
            }



            var total_amount=parseFloat(rate * qty).toFixed(2);
            $('#d_amount_1_'+number).val(total_amount);

            sum(1);
            dept_cost_amount('')

        }
        function dept_cost_amount(id,number)
        {

            var amount= $('#'+id).val();

            if (amount>0)
            {
                $('#dept_hidden_amount'+number).val(amount);
                $('#dept_amount'+number).text(amount);

                $('#cost_center_dept_amount'+number).text(amount);
           

                $('#cost_center_dept_hidden_amount'+number).val(amount);

            }

        }

        function calc_amount(id)
        {

            var  number= id.replace("d_amount_1_","");
            var amount= $('#'+id).val();

            amount= parseFloat(amount);
            if (isNaN(amount)==true)
            {
                amount=0;
            }

            var qty=$('#'+'qty_1_'+number).val();
            var totalrate=parseFloat(amount / qty);
            $('#rate_1_'+number).val(totalrate.toFixed(2));


            var amount= $('#d_amount_1_'+number+'').val();
            $('#dept_hidden_amount'+number).val(amount);
            $('#dept_amount'+number).text(amount);

            $('#cost_center_dept_amount'+number).text(amount);
            $('#cost_center_dept_hidden_amount'+number).val(amount);

        }
        function checked_unchekd(number,type)
        {
            if ($('#allocation' + number).is(":checked"))
            {
                if (type==0)
                {
                    var select_account= $("#account_id_1_"+number+" option:selected").text();
                }
                else
                {

                    var select_account= $("#"+type+" option:selected").text();
                }


                var amount= $('#d_amount_1_'+number+'').val();
                dept_cost_amount('d_amount_1_'+number+'',number);
                if (amount==0 || amount=='')
                {
                    amount= $('#c_amount_1_'+number+'').val();
                    dept_cost_amount('c_amount_1_'+number+'',number);
                }



//                $('#cost_center_dept_amount'+number).text(amount);
//                $('#dept_amount'+number).text(amount);
//                $('#cost_center_total_dept'+number).val(amount);


                $('#paragraph'+number).html('This Allocation For '+select_account +' And Amount is '+amount +' &darr;');

                // depratment and cost center show
                dept_allocation_amount_display(number);
                cost_center_allocation_amount_display(number);


                // allow null true or false
                $('#cost_center_check_box'+number).prop('checked', false);
                $('#dept_check_box'+number).prop('checked', false);
            }
            else
            {
                $('#dept_allocation'+number).css("display","none");
                $('#cost_center'+number).css("display","none");
                $('#paragraph'+number).html('');
                $('#cost_center_check_box'+number).prop('checked', true);
                $('#dept_check_box'+number).prop('checked', true);
            }

        }

        function dept_amount_validation()
        {
            var auth=1;


            for (i=1; i<=x; i++)
            {


                var item_amount= $('#d_amount_1_'+i+'').val();
                if (item_amount==0 || item_amount=='')
                {
                    item_amount= $('#c_amount_1_'+i+'').val();
                }

                if (typeof(item_amount) == 'undefined')
                {
                //    return auth;
                //    return false;

                }

                else
                {
                    item_amount= parseFloat(item_amount);
                    var dept_amount=$('#total_dept'+i).val();

                    item_amount=Math.round(item_amount);
                    dept_amount=Math.round(dept_amount);



                    if (item_amount!=dept_amount)
                    {

                        if($('#dept_check_box'+i).is(":checked"))
                        {


                        }
                        else
                        {

                            var dept_name = $('#account_id_1_' + i + ' :selected').text();
                            alert('Department Allocation Not Macth For ' + dept_name);
                            auth=0;

                            return false;

                        }
                    }
                }



            }
            return auth;
        }


        function cost_center_amount_validation()
        {
            var auth=1;

            for (i=1; i<=x; i++)
            {

                var item_amount= $('#d_amount_1_'+i+'').val();
                if (item_amount==0 || item_amount=='')
                {
                    item_amount= $('#c_amount_1_'+i+'').val();
                }

                if (typeof(item_amount) == 'undefined')
                {
                 //   return auth;
               //     return false;

                }


                else
                {
                    item_amount= parseFloat(item_amount);
                    var cost_center=$('#cost_center_total_dept'+i).val();


                    item_amount=Math.round(item_amount);
                    cost_center=Math.round(cost_center);

                    if (item_amount!=cost_center)
                    {

                        if($('#cost_center_check_box'+i).is(":checked"))
                        {


                        }
                        else
                        {
                            var dept_name = $('#account_id_1_' + i + ' :selected').text();

                            alert('Cost Center Allocation Not Macth For ' + dept_name);

                            auth=0;
                            return false;

                        }
                    }
                }


            }
            return auth;
        }


        function change_day()
        {

            var date=$('#pv_date_1').val();

            var d = new Date(date);

            var weekday = new Array(7);
            weekday[0] = "Sunday";
            weekday[1] = "Monday";
            weekday[2] = "Tuesday";
            weekday[3] = "Wednesday";
            weekday[4] = "Thursday";
            weekday[5] = "Friday";
            weekday[6] = "Saturday";

            var n = weekday[d.getDay()];

            document.getElementById("pv_day").value = n;
        }

        function show()
        {
            if ($('#items').is(':checked'))
            {
                $('.hidee').fadeIn(500);
            }
            else
            {
                $('.hidee').fadeOut(500);
            }
        }


        var supplier=[];
        var count_supplier=0;
        function check_supplier(id)
        {




            var counter = 1;
            $('.accounts').each(function (i, obj) {

                var idd = obj.id;
                var value = $('#' + idd).val();
                value=value.split('~');
                value=value[1];


                if (id != obj.id) {

                    var current=  $('#' + id).val();
                    current=current.split('~');
                    current=current[1];

                    var check_current_val=$('#' + id).val();
                    check_current_val=check_current_val.split('~');
                    check_current_val=check_current_val[1];

                    if (current == value && check_current_val!='') {

                        var supplier=$('#' + id).val();
                        supplier=supplier.split('~');

                        if (supplier[1]==1) {

                            alert('SUPPLIER"S ALREADY SELECTED ON LINE NO' + ' ' + counter);
                            $("#" + id).val('').trigger('change')
                            $('#' + id).select2.focus();
                            return false;
                        }


                    }


                }
                counter++;
            });


            //    alert(count_supplier);
//            var supplier_id= $('#'+id).val();
//            alert(supplier_id);
//            if (supplier==0)
//            {
//                var supplier_id= $('#'+id).val();
//                supplier_id= supplier_id.split(',');
//                if (supplier_id[1]!='')
//                {
//                    supplier=supplier_id[1];
//                }
//                else
//                {
//                    supplier=0;
//                }
//            }
//       else
//            {
//                alert('suppier Already Selected');
//                $("#" + id).val(0).trigger('change')
//
//
//            }
        }


        function get_supplier(id)
        {
            var supplier= $('#'+id).val();
            supplier_data=supplier.split(',');
            supplier=supplier_data[2];
            var   acc_id=supplier_data[1];
            if (supplier!=0)
            {
                var supplier_text_data=$("#payment_id option:selected").text();
                var supplier_text_data=supplier_text_data.split('=>');
                $('#adv_amount').val(supplier_text_data[3]);

                $('#account_id_1_2').val([supplier+'~1~'+acc_id]).trigger('change');


            }
            else
            {

                $('#supplier').val([0,0]).trigger('change');
                $('#adv_amount').val(0);
            }
        }

        function OpenMultiForm(Form)
        {



        }

        $('#FormOpen').on('change', function() {
            var FormName = this.value;
            FormName=FormName.trim();
            if (FormName=='Vendor')
            {
                showDetailModelOneParamerter('pdc/createSupplierFormAjax/jvs')
            }
            else if(FormName == 'Account')
            {
                showDetailModelOneParamerter('fdc/createAccountFormAjax/category_id_1_1/jvs')
            }
            //alert(this.value);
        });


        $("#supplier_id").change(function(){
            var SupplierId = $('#supplier_id').val();
            SupplierId=SupplierId.split(',');





            $("#account_id_1_2").val(SupplierId[1]+'~1~'+SupplierId[0]).trigger('change');
            $('#account_id_1_2').attr('disabled', 'true');
            if (SupplierId=='')
            {

                $('#account_id_1_2').attr('disabled', false);
            }

            SupplierId=SupplierId[0];
            if(SupplierId !="")
            {
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/get_refer',
                    type: "GET",
                    data: { SupplierId:SupplierId},
                    success:function(data)
                    {

                        $('#breakup_id').html(data);
                    }
                });
            }
            else{
                $('#breakup_id').html("");
                $('#breakup_id').html("<option value=''>Select</option>");
            }

        });
        function get_ledger_refrence_wise()
        {

            var breakup_id=$('#breakup_id').val();
            if (breakup_id=='new')
            {
                $('#new_ref').css('display','block');
                $('#new_ref').focus();
                return false;
            }
            var supplier_id=$('#supplier_id').val();

            supplier_id=supplier_id.split(',');
            supplier_id=supplier_id[0];



            breakup_id=breakup_id.split(',');
            breakup_id=breakup_id[1];



            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_ledger_refrence_wise',
                type: "GET",
                data: { breakup_id:breakup_id,supplier_id:supplier_id},
                success:function(data)
                {
                    $('#breakup_data').html(data);

                }
            });
        }

        $('#cashPaymentVoucherForm').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
