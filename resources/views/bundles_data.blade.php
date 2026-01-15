
<?php

$m=$_GET['m'];
use App\Helpers\CommonHelper;
?>
<div  class="modal fade"  id="budles_dataa" role="dialog" class="modal hide" data-backdrop="static" data-keyboard="false">
    <div style="width: 95%" class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Bundles</h2>
            </div>
            <div class="modal-body row">
                <?php echo Form::open(array('url' => 'sad/createbuldles?m='.$m.'','id'=>'subm','class'=>'stop'));?>
                <input type="hidden" id="sales_order_idd" name="sales_order_idd" />
                <input type="hidden" id="bundles_id" name="bundles_id" />
                <div class="row" id="bundle_table">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive" >
                            <div class="load">

                            </div>

                            <table class="table table-bordered" id="">
                                <thead>
                                <tr>

                                    <td colspan="" style="font-weight: bold;font-size: larger;background-color: darkgrey"><input class="form-control string clear" type="text" id="product_name" name="product_name"/> </td>
                                    <td style="width: 100px"><select class="form-control string" id="bundle_unit" name="bundle_unit">
                                            <option value="">Select</option>
                                            <?php foreach(CommonHelper::get_all_uom() as $row): ?>
                                                <option value="{{$row->id}}">{{$row->uom_name}}</option>
                                            <?php endforeach ?>
                                        </select></td>
                                    <td style="width: 100px">
                                        <input type="text" onkeyup="bundle_calculation('1');" onblur="bundle_calculation('1');"  class="form-control clear int" name="bundle_qty" id="bundle_qty" placeholder="QTY" min="1" value="0.00">
                                    </td>
                                    <td style="width: 120px" class="">
                                        <input onkeyup="bundle_calculation('1')" onblur="bundle_calculation('1')"  type="text"  class="form-control clear  int" name="bundle_rate" id="bundle_rate" placeholder="RATE" min="1" value="0.00">
                                    </td>
                                    <td style="width: 120px" class="">
                                        <input type="text" class="form-control  clear" name="bundle_amount" id="bundle_amount" placeholder="AMOUNT" min="1" value="0.00" readonly>
                                    </td>
                                    <td style="width: 120px" class="">
                                        <input type="text" onkeyup="bundle_discount_percent_cal(this.id);" onblur="bundle_discount_percent_cal(this.id)"  class="form-control  clear" name="bundle_discount_percent" id="bundle_discount_percent" placeholder="DISCOUNT" min="1" value="0.00">
                                    </td>
                                    <td style="width: 120px" class="">
                                        <input type="text" onkeyup="bundle_discount_amount_cal(this.id);" onblur="bundle_discount_amount_cal(this.id)"  class="form-control  clear" name="bundle_discount_amount" id="bundle_discount_amount" placeholder="DISCOUNT" min="1" value="0.00">
                                    </td>
                                    <td style="width: 120px" class="">
                                        <input type="text" class="form-control clear int" name="bundle_net_amount" id="bundle_net_amount" placeholder="NET AMOUNT" min="1" value="0.00" readonly>
                                    </td>


                                </tr>
                                </thead></table>


                            <table class="table table-bordered" id="">

                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 35%;">Item</th>
                                    <th style="width: 70px" class="text-center" >Uom<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th style="width: 107px" class="text-center" > QTY.<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Discount %<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Discount Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center">Net Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th style="width: 30px" class="text-center">Delete<span class="rflabelsteric"><strong>*</strong></span></th>
                                </tr>
                                </thead>
                                <tbody id="append_bundle">
                                <tr title="1">
                                    <td>
                                        <input  type="text" class="form-control sami string clear" name="bsub_ic_des[]" id="bitem_1" placeholder="ITEM">
                                        <input type="hidden" class="string clear" name="bitem_id[]" id="bsub_1">
                                    </td>

                                    <td>
                                        <input readonly type="text" class="form-control clear" name="buom_id[]" id="buom_id1" >
                                    </td>
                                    <td>
                                        <input type="text" onkeyup="bclaculation('1');bnet_calculation()" onblur="bclaculation('1');bnet_calculation()"  class="form-control clear int bqty" name="bactual_qty[]" id="bactual_qty1" placeholder="QTY" min="1" value="0.00">
                                    </td>
                                    <td class="">
                                        <input onkeyup="bclaculation('1');bnet_calculation()" onblur="bclaculation('1');bnet_calculation()"  type="text"  class="form-control clear  int brate" name="brate[]" id="brate1" placeholder="RATE" min="1" value="0.00">
                                    </td>
                                    <td class="">
                                        <input type="text" class="form-control bamount clear" name="bamount[]" id="bamount1" placeholder="AMOUNT" min="1" value="0.00" readonly>
                                    </td>
                                    <td class="">
                                        <input type="text" onkeyup="bdiscount_percent(this.id);bnet_calculation()" onblur="bdiscount_percent(this.id);bnet_calculation()"  class="form-control  clear bdiscount_percent" name="bdiscount_percent[]" id="bdiscount_percent1" placeholder="DISCOUNT" min="1" value="0.00">
                                    </td>
                                    <td class="">
                                        <input type="text" onkeyup="bdiscount_amount(this.id);bnet_calculation()" onblur="bdiscount_amount(this.id);bnet_calculation()"  class="form-control  clear bdiscount_amount" name="bdiscount_amount[]" id="bdiscount_amount1" placeholder="DISCOUNT" min="1" value="0.00">
                                    </td>
                                    <td class="">
                                        <input type="text" class="form-control clear bnet_amount_dis" name="bafter_dis_amount[]" id="bafter_dis_amount1" placeholder="NET AMOUNT" min="1" value="0.00" readonly>
                                    </td>
                                    <td style="background-color: #ccc"></td>
                                </tr>
                                </tbody>

                                <tbody>
                                <tr  style="font-size:large;font-weight: bold">
                                    <td class="text-center" colspan="2">Total</td>
                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_qty"/> </td>
                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_rate"/> </td>
                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_amount"/> </td>
                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_dscount_percent"/> </td>
                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_discount_amount"/> </td>
                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_net_amount"/> </td>

                                </tr>
                                </tbody>

                            </table>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <input type="button" class="btn btn-sm btn-primary" onclick="budles_more()" value="Add More Rows" />

                                </div>


                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    {{ Form::submit('Submit', ['class' => 'btn']) }}
                </div>
            </div>
            <?php echo Form::close();?>
            <span id="bundles"></span>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

</div>


@include('items_search_for_bundles')


<script>




    $('.sami').bind("enterKey",function(e){


        $('#items_searc_for_bundless').modal('show');


    });
    $('.sami').keyup(function(e){
        if(e.keyCode == 13)
        {
            selected_idd=this.id;
            $(this).trigger("enterKey");


        }

    });



    var bundles_countr = 1

    function budles_more()
    {
        bundles_countr++;
        $('#append_bundle').append('<tr id="bRemoveRows'+bundles_countr+'"  class="remove">' +
            '<td class="AutoCounter" title="">' +
            '<input  type="text" class="form-control sami string" name="bsub_ic_des[]" id="bitem_'+bundles_countr+'" placeholder="ITEM">' +
            '<input type="hidden" class="string" name="bitem_id[]" id="bsub_'+bundles_countr+'">'+
            '</td>' +
            '<td>' +
            '<input readonly type="text" class="form-control" name="uom_id[]" id="buom_id'+bundles_countr+'" >' +
            '</td>' +
            '<td>' +
            '<input type="text" onkeyup="bclaculation('+bundles_countr+');bnet_calculation()" onblur="bclaculation('+bundles_countr+');bnet_calculation()"  class="form-control int bqty" name="bactual_qty[]" id="bactual_qty'+bundles_countr+'" placeholder="QTY">' +
            '</td>' +
            '<td class="">' +
            '<input type="text" onkeyup="bclaculation('+bundles_countr+');bnet_calculation()" onblur="bclaculation('+bundles_countr+');bnet_calculation()"  class="form-control clear brate int" name="brate[]" id="brate'+bundles_countr+'" placeholder="RATE">' +
            '</td class="">' +
            '<td class="">' +
            '<input readonly type="text" class="form-control clear bamount" name="bamount[]" id="bamount'+bundles_countr+'" placeholder="AMOUNT">' +
            '</td>' +
            '<td class="">' +
            '<input type="text" onkeyup="bdiscount_percent(this.id);bnet_calculation()" onblur="bnet_calculation()" class="form-control bdiscount_percent clear" name="bdiscount_percent[]" id="bdiscount_percent'+bundles_countr+'" placeholder="DISCOUNT">' +
            '</td>' +
            '<td class="">' +
            '<input type="text" onkeyup="bdiscount_amount(this.id);bnet_calculation()" onblur="bnet_calculation()" class="form-control bdiscount_amount clear" name="bdiscount_amount[]" id="bdiscount_amount'+bundles_countr+'" placeholder="DISCOUNT">' +
            '</td>' +
            '<td class="">' +
            '<input readonly type="text" class="form-control bnet_amount_dis clear" name="bafter_dis_amount[]" id="bafter_dis_amount'+bundles_countr+'" placeholder="NET AMOUNT">' +
            '</td>' +
            '<td class="text-center">' +
            '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove'+bundles_countr+'" onclick="bRemoveSection('+bundles_countr+')"> - </button>' +
            '</td>' +
            '</tr>');
        $('.sami').bind("enterKey",function(e){


            $('#items_searc_for_bundless').modal('show');


        });
        $('.sami').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_idd=this.id;
                $(this).trigger("enterKey");


            }

        });






    }
    function bRemoveSection(Row) {
//            alert(Row);
        $('#bRemoveRows' + Row).remove();
        //   $(".AutoCounter").html('');

    }
    var numbers=0;
    function b_get_detail(id,number)
    {


        var item=$('#'+id).val();


        $.ajax({
            url:'{{url('/pdc/get_data')}}',
            data:{item:item},
            type:'GET',
            success:function(response)
            {

                var data=response.split(',');
                $('#buom_id'+number).val(data[0]);
                 numbers=number;

            }
        })



    }

    function bclaculation(number)
    {


        var  qty=$('#bactual_qty'+number).val();
        var  rate=$('#brate'+number).val();

        var total=parseFloat(qty*rate).toFixed(2);

        $('#bamount'+number).val(total);

        var amount = 0;
        count=1;
        $('.bnet_amount_dis').each(function (i, obj) {

            amount += +$('#'+obj.id).val();

            count++;
        });
        amount=parseFloat(amount);



        bdiscount_percent('bdiscount_percent'+number);

         //   bnet_calculation();
        //  toWords(1);
    }

   function bundle_calculation(id)
   {
       var  qty=$('#bundle_qty').val();
       var  rate=$('#bundle_rate').val();
       var total=parseFloat(qty*rate).toFixed(2);
       $('#bundle_amount').val(total);
       bundle_discount_percent_cal();
   }


    function bundle_discount_percent_cal()
    {

        var amount = $('#bundle_amount').val();
         var x = parseFloat($('#bundle_discount_percent').val());

        if (x >100)
        {
            alert('Percentage Cannot Exceed by 100');
            $('#bundle_discount_percent').val(0);
            x=0;
        }

        x=x*amount;
        var discount_amount =parseFloat( x / 100).toFixed(2);
        $('#bundle_discount_amount').val(discount_amount);
        var discount_amount=$('#bundle_discount_amount').val();

        if (isNaN(discount_amount))
        {

            $('#bundle_discount_amount').val(0);
            discount_amount=0;
        }



        var amount_after_discount=amount-discount_amount;

        $('#bundle_net_amount').val(amount_after_discount);
        var amount_after_discount=$('#bundle_net_amount').val();

        if (amount_after_discount==0)
        {
            $('#bundle_net_amount').val(amount);


        }

        else
        {


            $('#bundle_net_amount').val(amount_after_discount);
        }


    }

    function bundle_discount_amount_cal()
    {



            var amount=parseFloat($('#bundle_amount').val());

            var discount_amount=parseFloat($('#bundle_discount_amount').val());

            if (discount_amount > amount)
            {
                alert('Amount Cannot Exceed by '+amount);
                $('#bundle_discount_amount').val(0);
                discount_amount=0;
            }

            if (isNaN(discount_amount))
            {

                $('#bundle_discount_amount').val(0);
                discount_amount=0;
            }

            var percent=(discount_amount / amount *100).toFixed(2);
            $('#bundle_discount_percent').val(percent);
            var amount_after_discount=amount-discount_amount;
            $('#bundle_net_amount').val(amount_after_discount);
    }
    function bdiscount_percent(id)
    {
        var  number= id.replace("bdiscount_percent","");
        var amount = $('#bamount' + number).val();

        var x = parseFloat($('#'+id).val());

        if (x >100)
        {
            alert('Percentage Cannot Exceed by 100');
            $('#'+id).val(0);
            x=0;
        }

        x=x*amount;
        var discount_amount =parseFloat( x / 100).toFixed(2);
        $('#bdiscount_amount'+number).val(discount_amount);
        var discount_amount=$('#bdiscount_amount'+number).val();

        if (isNaN(discount_amount))
        {

            $('#bdiscount_amount'+number).val(0);
            discount_amount=0;
        }



        var amount_after_discount=amount-discount_amount;

        $('#bafter_dis_amount'+number).val(amount_after_discount);
        var amount_after_discount=$('#bafter_dis_amount'+number).val();

        if (amount_after_discount==0)
        {
          //  $('#bafter_dis_amount'+number).val(amount);
           // $('#bnet_amounttd_'+number).val(amount);
          //  $('#bnet_amount'+number).val(amount_after_discount);
        }

        else
        {


            $('#bafter_dis_amount'+number).val(amount_after_discount);
        }





        //  toWords(1);


    }

    var m ='{{$m}}';
// onmodal
    $('#budles_dataa').on('shown.bs.modal', function (e) {
        var number=selected_id.split('_');

        var sales_order_id=$('#sales_order_id').val();
        $('#sales_order_idd').val(sales_order_id);



        var product_id=$('#product_'+number[1]).val();

        if (product_id!='')
        {

           var product_name= $('#item_'+number[1]).val();

            $('#product_name').val(product_name);
        //    $("#bundle_table").css("display", "none");
            $('.load').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url:'{{url("sdc/get_bundels_data?param=")}}'+product_id+'&&m='+m,
                data:{product_id:product_id},

                success: function(response)
                {

                   $('#append_bundle').html(response);
                    $('.load').html('');
                    $('#bundles_id').val(product_id);
                    var rowCount = $('#append_bundle tr').length;

                    bundles_countr=rowCount;
                    $('#bundle_unit').val($('#bundle_uom').val());
                    $('#bundle_qty').val($('.bundle_qty').val());
                    $('#bundle_rate').val($('.bundle_rate').val());
                    $('#bundle_amount').val($('.bundle_amount').val());
                    $('#bundle_discount_percent').val($('.bundle_discount_percent').val());
                    $('#bundle_discount_amount').val($('.bundle_discount_amount').val());
                    $('#bundle_net_amount').val($('.bundle_net_amount').val());

                    bnet_calculation();

                    $('.sami').bind("enterKey",function(e){


                        $('#items_searc_for_bundless').modal('show');


                    });
                    $('.sami').keyup(function(e){
                        if(e.keyCode == 13)
                        {
                            selected_idd=this.id;
                            $(this).trigger("enterKey");


                        }

                    });

                  var sales_order_id=  $('#sales_order_id').val();
                   if (sales_order_id!='')
                   {
                       $('#sales_order_idd').val(sales_order_id);
                   }
                }
            });
        }
        else
        {
         //   $("#bundle_table").css("display", "block");
            $('.remove').remove();
            $('.clear').val('');
            bundles_countr=1;
            $('#bundles_id').val('');


        }
    })
    $('#subm').submit(function(e)
    {

        e.preventDefault();
        var validate=1;
        var me=$(this);

        $(".string").each(function() {
           if ($(this).val()=='')
           {

               validate=0;
           }

        });

        var total_net_amount=parseInt($('#total_net_amount').val());
        var bundle_net_amount=parseInt($('#bundle_net_amount').val());



        $(".int").each(function() {
            if (parseFloat($(this).val())==0 || $(this).val()=='')
            {

                validate=0;
                return false;
            }
        });

        if (total_net_amount!=bundle_net_amount)
        {

            alert('Amount Not Matched');
            validate=0;
            return false;
        }

        if (validate==0)
        {
            alert('Required All Proper Fields');
            e.preventDefault();
            return false;
        }


        $.ajax({
            url:me.attr('action'),
            data:me.serialize(),

            success: function(response)
            {

                    var data=response.split('@');
                    $('#sales_order_idd').val(data[2]);
                    $('#sales_order_id').val(data[2]);
                    var number=selected_id.split('_');

                    $('#'+selected_id).val(data[0]);


                    var bundle_unit= $( "#bundle_unit option:selected" ).text();
                    $('#uom_id'+number[1]).val(bundle_unit);


                        // for qty
                    var total_qty= $('#bundle_qty').val();
                    $('#actual_qty'+number[1]).val(total_qty);
                   $('#actual_qty'+number[1]).attr("readonly", true);
                        // endf



                // for rate
                var total_rate= parseFloat($('#bundle_rate').val());

               $('#rate'+number[1]).val(total_rate);
                $('#rate'+number[1]).attr("readonly", true);
               $('#rate'+number[1]).focus();
                // endf


                // for amount
                var amount= $('#bundle_amount').val();
                $('#amount'+number[1]).val(amount);
              $('#amount'+number[1]).attr("readonly", true);
                // endf

                // for discount percent
                var total_dscount_percent= $('#bundle_discount_percent').val();
               $('#discount_percent'+number[1]).val(total_dscount_percent);
                $('#discount_percent'+number[1]).attr("readonly", true);
                // endf


                // for discount amount
                var total_discount_amount= $('#bundle_discount_amount').val();
                $('#discount_amount'+number[1]).val(total_discount_amount);
               $('#discount_amount'+number[1]).attr("readonly", true);
                // endf


                // for after_dis_amount
                var total_net_amount= $('#bundle_net_amount').val();
                $('#after_dis_amount'+number[1]).val(total_net_amount);
                $('#after_dis_amount'+number[1]).attr("readonly", true);
                // endf
                net_amount();

                sales_tax();
                $('#product_'+number[1]).val(data[3]);
                    $('.remove').remove();
                    bundles_countr=1;
                    $('.clear').val('');
                    alert('Save Success');
                $('#item_'+number[1]).css("color", "red");


                    $('#budles_dataa').modal('hide');
            }
        });
    });

    $('#subm').on('keyup keypress', function(e) {

        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {

            e.preventDefault();
            return false;
        }
    });

        function bnet_calculation()
        {

            // rate
            var rate=0;
            var value=0;
            $('.brate').each(function (i, obj)
            {

                value=Number($('#'+obj.id).val());

                rate += +value;

            });
            $('#total_rate').val(rate);


            // qty
            var qty=0;
            $('.bqty').each(function (i, obj) {

                qty += +Number($('#'+obj.id).val());

            });
            $('#total_qty').val(qty);





            // amount
            var amount=0;
            $('.bamount').each(function (i, obj) {

                amount += +Number($('#'+obj.id).val());



            });
            $('#total_amount').val(amount);


            // discount_percentt
            var bdiscount_percent=0;
            $('.bdiscount_percent').each(function (i, obj) {

                bdiscount_percent += +Number($('#'+obj.id).val());



            });
            $('#total_dscount_percent').val(bdiscount_percent);


            // discount_amount
            var bdiscount_amount=0;
            $('.bdiscount_amount').each(function (i, obj) {

                bdiscount_amount += +Number($('#'+obj.id).val());



            });
            $('#total_discount_amount').val(bdiscount_amount);


            // net amount
            var net_amount=0;
            $('.bnet_amount_dis').each(function (i, obj) {

                net_amount += +Number($('#'+obj.id).val());



            });
            $('#total_net_amount').val(net_amount);

        }


    function bdiscount_amount(id)
    {

        var  number= id.replace("bdiscount_amount","");
        var amount=parseFloat($('#bamount'+number).val());

        var discount_amount=parseFloat($('#'+id).val());

        if (discount_amount > amount)
        {
            alert('Amount Cannot Exceed by '+amount);
            $('#bdiscount_amount'+number).val(0);
            discount_amount=0;
        }

        if (isNaN(discount_amount))
        {

            $('#bdiscount_amount'+number).val(0);
            discount_amount=0;
        }

        var percent=(discount_amount / amount *100).toFixed(2);
        $('#bdiscount_percent'+number).val(percent);
        var amount_after_discount=amount-discount_amount;
        $('#bafter_dis_amount'+number).val(amount_after_discount);


        $('#bnet_amounttd_'+number).val(amount_after_discount);
        $('#bnet_amount_'+number).val(amount_after_discount);

    }
</script>


