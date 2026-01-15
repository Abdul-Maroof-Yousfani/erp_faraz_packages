
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>
@extends('layouts.default')
@section('content')
    @include('modal')
    <?php
    $counter=0;
    $continues_count=0;
    $count_entry=   ReuseableCode::check_issuence_entry(Request::input('id'));
    ?>
            @if ($count_entry==0)
    <?php echo Form::open(array('url' => 'stad/add_issuence','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
    @else
                <?php echo Form::open(array('url' => 'stad/issuence_return','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
                @endif
    <div class="">
        <?php  $validation=true;?>
    @foreach($data as $row)

    <h4 style="text-align: center;font-weight: bold">Item :{{CommonHelper::get_item_name($row->product_id)}}</h4>
    <h4 style="text-align: center;font-weight: bold">Make QTY :{{$row->qty}}</h4>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive well">
            <?php $issuence_data=DB::Connection('mysql2')->table('issuence_for_production')->where('status',1)->where('master_id',$row->id);
                 
            ?>
            <table style="width: 85%;margin: auto" class="table table-bordered sf-table-list">
                <thead>
                <tr class="text-center">
                    <th colspan="5" class="text-center">Item Issued</th>
                    @if ($issuence_data->count()==0)
                    <th colspan="1" class="text-center">
                        <button type="button" class="btn btn-xs btn-primary" id="BtnAddMore" onclick="AddMoreRows('append{{$counter}}','{{$counter}}')">Add More</button>
                    </th>
                    @endif
                    <th class="text-center">
                        <span class="badge badge-success" id="span">1</span>
                    </th>
                </tr>

                <tr>
                    <th class="text-center" style="width: 30%">Item Name</th>
                    <th class="text-center" style="width: 180px;">Location</th>
                    <th class="text-center" style="width: 180px;">Batch Code</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center"> QTY</th>
                    <th style="display: none" class="text-center">Rate</th>
                    <th style="display: none" class="text-center">Amount</th>
                    @if ($issuence_data->count()>0)<th class="text-center"> Return QTY</th>@endif

                    <th class="text-center">-</th>
                </tr>
                </thead>
                <input type="hidden" name="count[]" value="{{$counter}}"/>
                <input type="hidden" name="product_id[]" value="{{$row->product_id}}"/>
                <input type="hidden" name="main_id" value="{{$row->master_id}}"/>
                <input type="hidden" name="master_id[]" value="{{$row->id}}"/>
                <input type="hidden" name="voucher_no" value="{{$row->voucher_no}}"/>
                <tbody id="append{{$counter}}">

                @if ($issuence_data->count()>0)
               @foreach($issuence_data->get() as $detail)
                   <input type="hidden" name="issuence_id[]" value="{{$detail->id}}"/>
                   <input type="hidden" name="sub_item_id[]" value="{{$detail->item_id}}"/>
                   <input type="hidden" name="issuence_master_id[]" value="{{$detail->master_id}}"/>
                   <input type="hidden" name="issuence_batch_code[]" value="{{$detail->batch_code}}"/>
                   <input type="hidden" name="issuence_warehouse_id[]" value="{{$detail->warehouse_id}}"/>

                <tr class="text-center AutoNo">
                    <td>
                        <input type="text" name="item_idd[]" value="{{CommonHelper::get_item_name($detail->item_id)}}" id="item_{{$continues_count}}" class="form-control sam_jass requiredField">
                        <input type="hidden" value="{{$detail->item_id}}" class="requiredField requiredField" name="item_id{{$counter}}[]" id="sub_{{$continues_count}}">
                    </td>
                    <td>
                        <select onchange="get_stock(this.id,'{{$continues_count}}')" name="warehouse_from{{$counter}}[]" id="warehouse_from{{$continues_count}}" class="form-control requiredField" style="width: 180px;">
                            <option value="">Select Warehouse</option>
                            <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                            <option @if($detail->warehouse_id==$Fil->id) selected @endif value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                            <?php endforeach;?>
                        </select>
                    </td>

                    <?php   ?>
                    <td><select onchange="get_stock_qty(this.id,'{{$continues_count}}')" class="form-control requiredField" name="batch_code{{$counter}}[]" id="batch_code{{$continues_count}}">

                            @foreach(ReuseableCode::get_bacth_code($detail->warehouse_id,$detail->item_id) as $batc_ode_data)
                            <option @if($detail->batch_code==$batc_ode_data->batch_code) selected @endif  value="{{$batc_ode_data->batch_code}}">{{$batc_ode_data->batch_code}}</option>
                                @endforeach

                        </select></td>

                    <?php $stock=ReuseableCode::get_stock($detail->item_id,$detail->warehouse_id,0,$detail->batch_code); ?>
                    <td>
                        <input type="text" name="in_stock_qty{{$counter}}[]" id="in_stock_qty{{$continues_count}}" class="form-control requiredField" value="{{$stock}}" readonly>
                    </td>
                    <?php $return_qty=DB::Connection('mysql2')->table('issuence_for_production')->where('id',$detail->id)->sum('return_qty'); ?>
                    <td>
                        <input readonly onkeyup="check_qty(this.id,'{{$counter}}')" value="{{$detail->qty-$return_qty}}" type="number" name="qty{{$counter}}[]" id="qty{{$continues_count}}" class="form-control requiredField SendQty" step="any" min>
                    </td>
                    <td style="display: none">
                        <input  readonly type="number" name="rate{{$counter}}[]" id="rate{{$counter}}" class="form-control">
                    </td>

                    <td>
                        <input @if ($row->pi_no!='') style="display: none" @endif onkeyup="return_qty('{{$continues_count}}')" type="number" name="return[]" id="return{{$continues_count}}" class="form-control">
                    </td>



                    <td style="display: none">
                        <input  readonly type="number" class="form-control">
                    </td>


                    <td>-</td>
                </tr>
                   <?php $continues_count++ ?>
                @endforeach
                @else

                    <input type="hidden" name="update_id{{$counter}}[]" value="{{0}}"/>
                    <tr class="text-center AutoNo">
                        <td>
                            <input type="text" name="item_idd[]" value="" id="item_{{$continues_count}}" class="form-control sam_jass requiredField">
                            <input type="hidden" value="" class="requiredField requiredField" name="item_id{{$counter}}[]" id="sub_{{$continues_count}}">
                        </td>
                        <td>
                            <select onchange="get_stock(this.id,'{{$continues_count}}')" name="warehouse_from{{$counter}}[]" id="warehouse_from{{$continues_count}}" class="form-control requiredField" style="width: 180px;">
                                <option value="">Select Warehouse</option>
                                <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                                <option  value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                <?php endforeach;?>
                            </select>
                        </td>

                        <?php   ?>
                        <td><select onchange="get_stock_qty(this.id,'{{$continues_count}}')" class="form-control requiredField" name="batch_code{{$counter}}[]" id="batch_code{{$continues_count}}">


                                    <option>Select &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>


                            </select></td>

                        <?php  ?>
                        <td>
                            <input type="text" name="in_stock_qty{{$counter}}[]" id="in_stock_qty{{$continues_count}}" class="form-control requiredField" value="" readonly>
                        </td>
                        <td>
                            <input onkeyup="check_qty(this.id,'{{$continues_count}}')" value="" type="number" name="qty{{$counter}}[]" id="qty{{$continues_count}}" class="form-control requiredField SendQty" step="any" min>
                        </td>
                        <td style="display: none">
                            <input  readonly type="number" name="rate{{$counter}}[]" id="rate{{$continues_count}}" class="form-control">
                        </td>
                        <td style="display: none">
                            <input readonly type="number" name="amount{{$counter}}[]" id="amount{{$continues_count}}" class="form-control">
                        </td>


                        <td>-</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

            <?php $continues_count++; $counter++; ?>
        @endforeach

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                <button type="submit" id="" class="btn btn-success">Submit</button>

            </div>

        <?php echo Form::close();?>
</div>







    <script>

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

        var Counter = '{{$continues_count-1}}';
        function AddMoreRows(append,index)
        {
            Counter++;
            $('#'+append).append('<tr class="text-center AutoNo" id="RemoveRow'+Counter+'">' +
                    '<td>' +
                    '<input type="text" name="item_idd'+index+'[]" id="item_'+Counter+'" class="form-control sam_jass requiredField"><input type="hidden" class="requiredField" name="item_id'+index+'[]" id="sub_'+Counter+'">' +
                    '</td>' +
                    '<td>' +
                    '<select onchange="get_stock(this.id,'+Counter+')" name="warehouse_from'+index+'[]" id="warehouse_from'+Counter+'" class="form-control" style="width: 180px;">' +
                    '<option value="">Select Warehouse</option>'+
                    <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                    '<option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '<td><select onchange="get_stock_qty(this.id,'+Counter+')" class="form-control requiredField" name="batch_code'+index+'[]"' +
                    'id="batch_code'+Counter+'">'+
                    '<option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>'+
                    '</select></td>'+
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="in_stock_qty'+index+'[]" id="in_stock_qty'+Counter+'" class="form-control requiredField" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input onkeyup="check_qty(this.id,'+Counter+')" type="number" name="qty'+index+'[]" id="qty'+Counter+'" class="form-control requiredField SendQty">' +
                    '</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveRows('+Counter+')">-</button>' +
                    '</td>'+
                    '</tr><input type="hidden" name="update_id'+index+'" value="{{0}}"/>');

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

        }

        function RemoveRows(Rows)
        {
            $('#RemoveRow'+Rows).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
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
        $( "form" ).submit(function( event ) {
            var purchaseRequest = new Array();
            var val;
            //$("input[name='demandsSection[]']").each(function(){
            purchaseRequest.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of purchaseRequest) {
                jqueryValidationCustom();

                if(validate == 0)
                {

                  }
                else
                {

                    event.preventDefault();
                    return false;

                }
                }

        });

        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });


        function return_qty(number)
        {
            var return_qty=parseFloat($('#return'+number).val());
            if (isNaN(return_qty))
            {
                return_qty=0;
            }
            var actual_qty=parseFloat($('#qty'+number).val());

            if (return_qty > actual_qty)
            {
                alert('QTY can not greater than '+actual_qty);
                $('#return'+number).val(0);
            }

        }
    </script>
@endsection