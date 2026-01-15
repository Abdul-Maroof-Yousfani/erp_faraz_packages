<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');


use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
$m=Session::get('run_company');
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Internal Consumption Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'prad/update_internal_consum','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <div class="row">
                                    <?php $uniq=PurchaseHelper::get_unique_no_internal_consumtion(date('y'),date('m')) ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label for="">Internal Consumption No</label>
                                        <input type="text"  id="tr_no" name="tr_no" value="{{strtoupper($main_data->voucher_no)}}" class="form-control requiredField" readonly>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label for="">Internal Consumption  Date</label>
                                        <input type="date" class="form-control requiredField" id="tr_date" name="tr_date" value="{{ $main_data->voucher_date }}">
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label for="">Remarks</label>
                                        <textarea type="text" name="description"  id="description" class="form-control requiredField">{{ $main_data->description }}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{ $main_data->id }}"/>
                                <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                <tr class="text-center">
                                                    <th colspan="5" class="text-center">Internal Consumption Detail</th>
                                                    <th colspan="1" class="text-center">
                                                        <button type="button" class="btn btn-xs btn-primary" id="BtnAddMore" onclick="AddMoreRows()">Add More</button>
                                                    </th>
                                                    <th class="text-center">
                                                        <span class="badge badge-success" id="span">1</span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" style="width: 30%">Item Name</th>
                                                    <th class="text-center" style="width: 180px;">Location From</th>
                                                    <th class="text-center" style="width: 180px;">Batch Code</th>
                                                    <th class="text-center">In Stock Qty</th>
                                                    <th class="text-center">Transfer Qty</th>
                                                    <th style="display: none" class="text-center">Rate</th>
                                                    <th style="display: none" class="text-center">Amount</th>
                                                    <th class="text-center" style="width: 180px;">Chart Of Account</th>
                                                    <th style="" class="text-center">Desc</th>
                                                    <th class="text-center">-</th>
                                                </tr>
                                                </thead>
                                                <tbody id="AppendHtml">
                                                <?php $count=1; ?>
                                                @foreach($child_data as $row)
                                                <tr class="text-center AutoNo">
                                                    <td>
                                                        <input type="text" name="item_idd[]" id="item_{{ $count }}" value="{{ CommonHelper::get_item_name($row->item_id) }}" class="form-control sam_jass requiredField">
                                                        <input type="hidden" value="{{ $row->item_id }}" class="requiredField requiredField" name="item_id[]" id="sub_1">
                                                    </td>
                                                    <td>
                                                        <select onchange="get_stock(this.id,'{{ $count }}')" name="warehouse_from[]" id="warehouse_from{{ $count }}" class="form-control requiredField">
                                                            <option value="">Select Warehouse</option>
                                                            <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                                                            <option @if($row->warehouse_from==$Fil->id) selected @endif value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>

                                                    <td><select onchange="get_stock_qty(this.id,'{{ $count }}')" class="form-control requiredField" name="batch_code[]" id="batch_code{{ $count }}">
                                                            <option value="{{ $row->batch_code }}">{{ $row->batch_code }}</option>

                                                        </select></td>
                                                    <td>
                                                        <input type="text" name="in_stock_qty[]"  id="in_stock_qty{{ $count }}" class="form-control" readonly>
                                                    </td>
                                                    <td>
                                                        <input onkeyup="check_qty(this.id,1)" value="{{ $row->qty }}" type="text" name="qty[]" id="qty{{ $count }}" class="form-control requiredField SendQty" step="any" min>
                                                    </td>
                                                    <td style="display: none">
                                                        <input  readonly type="number" name="rate[]" value="{{ $row->rate }}" id="rate{{ $count }}" class="form-control">
                                                    </td>
                                                    <td style="display: none">
                                                        <input readonly type="number" name="amount[]" id="amount{{ $count }}" value="{{ $row->amount }}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <select name="warehouse_to[]" id="warehouse_to{{ $count }}" class="form-control requiredField" style="width: 180px;">
                                                            <option value="">Select Warehouse</option>
                                                            <?php foreach(CommonHelper::get_all_account() as $Fil):?>
                                                            <option @if($row->acc_id==$Fil->id) selected @endif value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>
                                                    <td><textarea name="des[]">{{ $row->desc }}</textarea></td>
                                                    <td>-</td>
                                                </tr>
                                                <input type="hidden" name="data_id[]" value="{{ $row->id }}"/>
                                                    <?php $count++ ?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

                                    <button type="submit" id="" class="btn btn-success">Submit</button>
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
    <script>
        var Counter = '{{ $count-1 }}';
        function AddMoreRows()
        {
            Counter++;
            $('#AppendHtml').append('<tr class="text-center AutoNo" id="RemoveRow'+Counter+'">' +
                    '<td>' +
                    '<input type="text" name="item_idd[]" id="item_'+Counter+'" class="form-control sam_jass requiredField"><input type="hidden" class="requiredField" name="item_id[]" id="sub_'+Counter+'">' +
                    '</td>' +
                    '<td>' +
                    '<select onchange="get_stock(this.id,'+Counter+')" name="warehouse_from[]" id="warehouse_from'+Counter+'" class="form-control" style="width: 180px;">' +
                    '<option value="">Select Warehouse</option>'+
                    <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                    '<option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '<td><select onchange="get_stock_qty(this.id,'+Counter+')" class="form-control requiredField" name="batch_code[]"' +
                    'id="batch_code'+Counter+'">'+
                    '<option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>'+
                    '</select></td>'+
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="in_stock_qty[]" id="in_stock_qty'+Counter+'" class="form-control" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input onkeyup="check_qty(this.id,'+Counter+')" type="text" name="qty[]" id="qty'+Counter+'" class="form-control requiredField SendQty">' +
                    '</td>' +
                    '<td style="display: none">' +
                    '<input readonly type="number" name="rate[]" id="rate'+Counter+'" class="form-control">' +
                    '</td>' +
                    '<td style="display: none">' +
                    '<input readonly type="number" name="amount[]" id="amount'+Counter+'" class="form-control">' +
                    '</td>' +
                    '<td>' +
                    "<select name='warehouse_to[]' id='warehouse_to"+Counter+"' class='form-control requiredField' style='width: 180px;'>" +
                    '<option value="">Select Warehouse</option>'+
                    <?php foreach(CommonHelper::get_all_account() as $Fil):?>
                    "<option value='<?php echo $Fil->id?>'><?php echo $Fil->name?></option>"+
                    <?php endforeach;?>
                    '</select>' +
                    '</td>' +
                    '<td><textarea name="des[]"></textarea></td>'+
                    '<td>' +
                    '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveRows('+Counter+')">-</button>' +
                    '</td>'+
                    '</tr> <input type="hidden" name="data_id[]" value="{{ 0 }}"/>');
            $('#warehouse_to'+Counter).select2();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);


            $('.sam_jass').bind("enterKey",function(e){


                $('#items').modal('show');
                e.preventDefault();

            });
            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKey");
                    e.preventDefault();

                }

            });
            $('.SendQtyy').on('keypress', function (event) {
                var regex = new RegExp("^[a-zA-Z0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
        }
        $('.SendQtyy').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        function RemoveRows(Rows)
        {
            $('#RemoveRow'+Rows).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }
        $(document).ready(function(){
            $('#supplier').select2();
        });

        function getGrnNoBySupplier() {

            $('.loadGoodsReceiptNoteDetailSection').html('');
            var supplier_id=$('#supplier').val();
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/getGrnNoBySupplier',
                type: "GET",
                data: { supplier_id:supplier_id},
                success:function(data)
                {
                    $('#grn_no').html(data);
                    $('#grn_no').select2();
                }
            });
        }

        function loadGoodsReceiptNoteDetailByGrnNo(){


            var GrnNo = $('#grn_no').val();
            var m = '<?php echo $m?>';
            if(GrnNo == ''){
                alert('Please Select Purchase Request No');
                $('.loadGoodsReceiptNoteDetailSection').html('');
            }else{
                $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormGoodsReceiptNoteDetailByGrnNo',
                    type: "GET",
                    data: { GrnNo:GrnNo,m:m},
                    success:function(data) {
                        $('.loadGoodsReceiptNoteDetailSection').html(data);
                    }
                });
            }
        }


        $( "form" ).submit(function( event ) {
            var validate=validatee();

            if (validate==true)
            {

            }
            else
            {
                return false;
            }

        });
        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        function validatee()
        {
            var validate=true;
            $( ".amount" ).each(function() {
                var id=this.id;
                if($('#'+id).prop("checked") == true)
                {

                    id=id.replace('enable_disable_','');
                    var amount=$('#return_qty_'+id).val();

                    if (amount <= 0 || amount=='')
                    {
                        $('#return_qty_'+id).css('border', '3px solid red');

                        validate=false;
                    }
                    else
                    {
                        $('#return_qty_'+id).css('border', '');

                        if ($('#Remarks').val()=='')
                        {
                            $('#Remarks').css('border', '3px solid red');

                            validate=false;
                        }
                    }



                }

            });
            return validate;
        }


        $('.sam_jass').bind("enterKey",function(e){


            $('#items').modal('show');
            e.preventDefault();

        });
        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");
                e.preventDefault();

            }

        });


        function get_stock(warehouse,number)
        {
            $('#in_stock_qty'+number).val(0);

            $('#warehouse_to'+number+'').val('');
            $('#warehouse_to'+number+' option').prop('disabled', false);
            var warehouse=$('#'+warehouse).val();
            var item=$('#sub_'+number).val();

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise',
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data)
                {


                    //   data=data.split('/');
                    $('#batch_code'+number).html(data);
                    // $('#rate'+number).val(data[1]);

                    $('#warehouse_to'+number+' option[value="'+warehouse+'"]').prop('disabled', true)
                    check_qty('qty'+number,number);
                }
            });

        }


        function get_stock_qty(warehouse,number)
        {


            var warehouse=$('#warehouse_from'+number).val();
            var item=$('#sub_'+number).val();
            var batch_code=$('#batch_code'+number).val();


            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data)
                {

                    //   $('#batch_code'+number).html(data);

                    data=data.split('/');
                    $('#in_stock_qty'+number).val(data[0]);
                    $('#rate'+number).val(data[1]);
                    //     var amount=data[0]*data[1];
                    //     $('#net_amount'+number).val(amount);
                    if (data[0]==0)
                    {
                        $("#"+item).css("background-color", "red");
                    }
                    else
                    {
                        $("#"+item).css("background-color", "");
                    }

                }
            });

        }
        function check_qty(id,number)
        {
            var qty=parseFloat($('#'+id).val());
            var instock=parseFloat($('#in_stock_qty'+number).val());

            if (qty>instock)
            {
                alert('Transferd QTY can not greater than actual qty');
                $('#'+id).val(0);
                $('#amount'+number).val(0);
            }
            else
            {
                var rate = parseFloat( $('#rate'+number).val());
                var total=(qty*rate).toFixed(2)
                $('#amount'+number).val(total);
            }
        }


        $(function() {


            $('#warehouse_to1').select2();
            $(".btn-success").click(function(e){
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if(validate == 0){

                        vala = 0;
                        var flag = false;
                        $('.SendQty').each(function(){
                            vala = parseFloat($(this).val());
                            if(vala == 0)
                            {
                                alert('Please Enter Correct Transfer Qty....!');
                                $(this).css('border-color','red');
                                flag = true;
                                return false;
                            }
                            else{
                                $(this).css('border-color','#ccc');
                            }
                        });
                        if(flag == true)
                        {return false;}

                    }
                    else
                    {
                        return false;
                    }
                }

            });
        });
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection