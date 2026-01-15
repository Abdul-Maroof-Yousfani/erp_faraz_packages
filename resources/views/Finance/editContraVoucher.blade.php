<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2');
    <div class="well">
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
                                    <span class="subHeadingLabelClass">Create Contra Voucher Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'fad/updateContraVoucherDetail?m='.$m.'','id'=>'updateContraVoucherDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="cvsSection[]" class="form-control requiredField" id="cvsSection" value="1" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">


                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">CV No</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input type="hidden" name="edit_id" value="{{$id}}">
                                                            <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
                                                                    name="" id="" value="{{strtoupper($contra->cv_no)}}" />
                                                            <input type="hidden" name="ContraNo" id="ContraNo" value="<?php echo $contra->cv_no?>">
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">CV Date.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input onblur="change_day()" onchange="change_day()"  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="rv_date_1" id="rv_date_1" value="<?php echo $contra->cv_date ?>" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">CV Day.</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">With Cheque</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input @if ($contra->cheque_no!='')  checked @endif  type="checkbox" class="" value="1" name="cheque_status"  />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_nature">
                                                            <label class="sf-label">Cheque No.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="{{$contra->cheque_no}}" />
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_nature">
                                                            <label class="sf-label">Cheque Date.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="<?php echo $contra->cheque_date?>" />
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
                                                                            <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                                                                            </th>
                                                                            <th class="text-center" style="width:150px;">Current Bal<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>

                                                                            <th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="addMoreRvsDetailRows_1" id="addMoreRvsDetailRows_1">
                                                                        <?php

                                                                        $counter=1;
                                                                        $total_debit=0;
                                                                        $total_credit=0;

                                                                        foreach ($contra_data as $contra_dataFiltr):
                                                                        ?>
                                                                        <input type="hidden" name="rvsDataSection_1[]" class="form-control requiredField" id="rvsDataSection_1" value="<?php echo $counter?>" />
                                                                        <tr id="removeRvsRows_1_<?php echo $counter?>">
                                                                            <td>
                                                                                <select onchange="get_current_amount(this.id);" style="width: 100%" class="form-control requiredField select2 accounts" name="account_id_1_<?php echo $counter?>" id="account_id_1_<?php echo $counter?>">
                                                                                    <option value="">Select Account</option>
                                                                                    @foreach($accounts as $key => $y)
                                                                                        <option <?php if($y->id == $contra_dataFiltr['acc_id']){echo "selected";}?> value="{{ $y->id }}">{{$y->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>

                                                                            <input type="hidden" id="current_amount_hidden<?php echo $counter ?>"/>
                                                                            <script>
                                                                                //get_current_amount('< ?php echo $y->id ?>');
                                                                            </script>
                                                                            <td>
                                                                                <input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $counter ?>"  value="" required="required"/>
                                                                            </td>


                                                                            <td>
                                                                                <input @if($contra_dataFiltr['debit_credit']==1) <?php $total_debit+=$contra_dataFiltr['amount'] ?> value="{{$contra_dataFiltr['amount']}}" @else readonly @endif    onfocus="mainDisable('c_amount_1_<?php echo $counter ?>','d_amount_1_<?php echo $counter ?>');"
                                                                                       placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text"
                                                                                       name="d_amount_1_<?php echo $counter ?>" id="d_amount_1_<?php echo $counter ?>" onkeyup="sum('1');calculation(this.id,'1');calc_amount(this.id);"  required="required"/>
                                                                            </td>

                                                                            <td>
                                                                                <input   @if($contra_dataFiltr['debit_credit']==0) <?php $total_credit+=$contra_dataFiltr['amount'] ?> value="{{$contra_dataFiltr['amount']}}"   @else  readonly @endif  onfocus="mainDisable('d_amount_1_<?php echo $counter ?>','c_amount_1_<?php echo $counter ?>');" placeholder="Credit" class="form-control requiredField c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $counter ?>" id="c_amount_1_<?php echo $counter ?>" onkeyup="sum('1');calculation(this.id,'0');dept_cost_amount(this.id,'<?php echo $counter ?>')" value="" required="required"/>
                                                                            </td>
                                                                            <?php if($counter > 2):?>
                                                                            <td class="text-center"><a href="#" onclick="removeRvsRows('1','<?php echo $counter?>'),sum('1')" class="btn btn-xs btn-danger">Remove</a></td>
                                                                            <?php else:?>
                                                                            <td class="text-center">---</td>
                                                                            <?php endif;?>
                                                                        </tr>
                                                                        <script type="text/javascript">

                                                                            //get_detail_purchase_voucher(< ?php echo $jv_dataFiltr['item_id']?>);
                                                                        </script>
                                                                        <?php
                                                                        $counter++;
                                                                        endforeach;?>
                                                                        </tbody>
                                                                    </table>
                                                                    <input type="hidden" id="counter" value="{{$counter}}"/>
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
                                                                                        value="{{$total_debit}}"/>
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
                                                                                        value="{{$total_credit}}"/>
                                                                            </td>
                                                                            <td class="diff" style="width:150px;font-size: 20px;"></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td colspan="12" style="font-size: 20px;color: navy;" id="rupees"></td>
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
                                                    <input type="button" class="btn btn-sm btn-primary" onclick="addMoreRvsDetailRows('1')" value="Add More CV's Rows" />
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="sf-label">Description</label>
                                                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                        <textarea name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"><?php echo $contra->description?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cvsSection"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

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
    <script>
        $(document).ready(function() {

            $('input[name="cheque_status"]').click(function(){
                if($(this).prop("checked") == true){
                    $('.payment_nature').fadeIn(500);
                    $("#cheque_no_1").addClass("requiredField");
                    $("#cheque_date_1").addClass("requiredField");
                    $('#with_cheque').val(0);
                }
                else if($(this).prop("checked") == false){
                    $('.payment_nature').fadeOut(500);
                    $("#cheque_no_1").removeClass("requiredField");
                    $("#cheque_date_1").removeClass("requiredField");
                    $('#with_cheque').val(1);


                }
            });
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
            var counter=$('#counter').val();
            for(i=1; i<=counter; i++)
            {
                $('#d_amount_1_'+counter).number(true,2);
                $('#c_amount_1_'+counter).number(true,2);


            }
            $('#d_t_amount_1').number(true,2);
            $('#c_t_amount_1').number(true,2);

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


        function change_day()
        {

            var date=$('#rv_date_1').val();

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

        var x = '<?php echo $counter-1 ?>';
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
                    $('#account_id_1_'+x+'').focus();
                    $('#c_amount_1_'+x).number(true,2);
                    $('#d_amount_1_'+x).number(true,2);
                }
            });
        }

        function removeRvsRows(id,counter){
            var elem = document.getElementById('removeRvsRows_'+id+'_'+counter+'');
            elem.parentNode.removeChild(elem);
        }
        function removeRvsSection(id){
            var elem = document.getElementById('bankRvs_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>


    <script type="text/javascript">

        $('.select2').select2();



    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection