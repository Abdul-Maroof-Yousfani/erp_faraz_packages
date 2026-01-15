<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    <?php



    $data=DB::Connection('mysql2')->table('subitem')
            ->where('sub_ic', 'like', '%' . 'elbow 90d' . '%')->orderBy('item_master_code','ASC')->get()->take(20);
    $VoucherNo = CommonHelper::get_unique_import_no(date('y'),date('m'));

    ?>
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Import Document</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @include('Sales.import_po_other')
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>    
        <script>
            var Counter = 1;


            function AddMoreDetails()
            {
                Counter++;
                $('#AppnedHtml').append(
                        '<tr class="RemoveRows'+Counter+' remove">' +
                        '<td class="text-center AutoCounter">'+Counter+'</td>'+
                        '<td>' +
                        '<input type="text" onchange="get_detail(this.id,'+Counter+')" class="form-control sam_jass" name="sub_ic_des[]" id="item_'+Counter+'">' +
                        '<input type="hidden" class="requiredField" name="item_id[]" id="sub_'+Counter+'">'+
                        '</td>' +
                        '<td>' +
                        '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'">' +
                        '</td>' +
                        '<td>' +
                        '<input onkeyup="Calc('+Counter+')" type="number" class="form-control requiredField" name="system_qty[]" id="system_qty'+Counter+'" placeholder="System Quantity" step="any">' +
                        '</td>' +
                        '<td>' +
                        '<input onkeyup="Calc('+Counter+')" type="number" class="form-control requiredField" name="foreign_currency[]" id="foreign_currency'+Counter+'" placeholder="Foreign Currency" step="any">' +
                        '</td>' +
                        '<td>' +
                        '<input readonly type="text" class="form-control" name="total_amount[]" id="total_amount'+Counter+'" placeholder="Total Amount" step="any">' +
                        '</td>' +
//                    '<td>' +
//                    '<input readonly type="text" class="form-control" name="grand_total[]" id="grand_total'+Counter+'" placeholder="Grand Total">' +
//                    '</td>' +
                        '<td class="text-center">' +
                        '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')">&times;</button>' +
                        '</td>' +
                        '</tr>' +
                        '</tr>');


                $('#category_'+Counter).select2();
                $('#subcategory_'+Counter).select2();
                var AutoCount = 1;
                $(".AutoCounter").each(function(){
                    AutoCount++;
                    $(this).html(AutoCount);
                });


                $('.sam_jass').bind("enterKey",function(e){


                    $('#items').modal('show');
                });
                $('.sam_jass').keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        selected_id=this.id;
                        $(this).trigger("enterKey");
                    }
                });
            }
            function RemoveSection(Row) {
//            alert(Row);
                $('.RemoveRows' + Row).remove();
                $(".AutoCounter").html('');
                var AutoCount = 1;
                $(".AutoCounter").each(function () {
                    AutoCount++;
                    $(this).html(AutoCount);
                });
            }





            function clear_fiel(id)
            {
                $('#'+id).prop('readonly', false);
                $('#'+id).val('');

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


            $('.stop').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $(function() {



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

                            $('#cashPaymentVoucherForm').submit();
                        }
                        else
                        {
                            return false;
                        }
                    }

                });
            });








        </script>


        <script>
            function get_detail(id,number)
            {
                var item=$('#'+id).val();
                $.ajax({
                    url:'{{url('/pdc/get_data')}}',
                    data:{item:item},
                    type:'GET',
                    success:function(response)
                    {
                        var data=response.split(',');
                        $('#uom_id'+number).val(data[0]);
                        $('#last_ordered_qty'+number).val(data[1]);
                        $('#last_received_qty'+number).val(data[2]);
                        $('#closing_stock'+number).val(data[3]);
                    }
                })
            }


            $('#TestingForm').submit('click', function (event) {
                // using this page stop being refreshing
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url:'{{url('/sad/addTestForm')}}',
                    data: $('#TestingForm').serialize(),
                    success: function (data) {
                        if(data == 'yes')
                        {
                            alert('Record Submit');
                            $('.remove').remove();
                            $('.clear').val('');

                        }
                    },

                    error: function (data) {
                        alert('something went wrong');
                    }

                });
            });


        </script>

        <script>
            function Calc(Row)
            {

                var ForeignCurrency = parseFloat($('#foreign_currency'+Row).val());
                if(isNaN(ForeignCurrency))ForeignCurrency=0;
                var SystemQty = parseFloat($('#system_qty'+Row).val());
                if(isNaN(SystemQty))SystemQty=0;
                var TotalAmount = parseFloat(ForeignCurrency*SystemQty).toFixed(2);
                $('#total_amount'+Row).val(TotalAmount);

            }


            function hide_unhide(append,hide)
            {

                $("."+append).fadeIn(500);
                $("."+hide).fadeOut(500);
            }
        </script>


        <script type="text/javascript">

            $('.select2').select2();
        </script>



        <script>




            function add_more_by_weight()
            {
                Counter++;
                $('#append_by_weight').append(
                        '<tr class="RemoveRows'+Counter+' remove">' +
                        '<td class="text-center AutoCounter">'+Counter+'</td>'+
                        '<td>' +
                        '<input type="text" onchange="get_detail(this.id,'+Counter+')" class="form-control sam_jass" name="wsub_ic_des[]" id="item_'+Counter+'">' +
                        '<input type="hidden" class="requiredField" name="witem_id[]" id="sub_'+Counter+'">'+
                        '</td>' +
                        '<td>' +
                        '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'">' +
                        '</td>' +
                        '<td>' +
                        '<input  onkeyup="calc_new('+Counter+')" type="number" class="form-control requiredField" name="wsystem_qty[]" id="system_qty'+Counter+'" placeholder="System Quantity" step="any">' +
                        '</td>' +

                        '<td>' +
                        '<input onkeyup="calc_new('+Counter+')" type="number" class="form-control requiredField" name="wtotal_weight[]" id="total_weight'+Counter+'" placeholder="" step="any">' +
                        '</td>' +

                        '<td>' +
                        '<input onkeyup="calc_new('+Counter+')" type="number" class="form-control requiredField" name="wtotal_rate_per_weight[]" id="total_rate_per_weight'+Counter+'" placeholder="" step="any">' +
                        '</td>' +

                        '<td>' +
                        '<input onkeyup="" readonly type="number" class="form-control requiredField" name="was_per_weight[]" id="as_per_weight'+Counter+'" placeholder="" step="any">' +
                        '</td>' +
                        '<td>' +
                        '<input onkeyup="Calc('+Counter+')" type="number" class="form-control requiredField" name="wforeign_currency[]" id="foreign_currency'+Counter+'" placeholder="Foreign Currency" step="any">' +
                        '</td>' +
                        '<td>' +
                        '<input readonly type="text" class="form-control" name="wtotal_amount[]" id="total_amount'+Counter+'" placeholder="Total Amount" step="any">' +
                        '</td>' +
//                    '<td>' +
//                    '<input readonly type="text" class="form-control" name="grand_total[]" id="grand_total'+Counter+'" placeholder="Grand Total">' +
//                    '</td>' +
                        '<td class="text-center">' +
                        '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')">&times;</button>' +
                        '</td>' +
                        '</tr>' +
                        '</tr>');



                var AutoCount = 1;
                $(".AutoCounter").each(function(){
                    AutoCount++;
                    $(this).html(AutoCount);
                });


                $('.sam_jass').bind("enterKey",function(e){


                    $('#items').modal('show');
                });
                $('.sam_jass').keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        selected_id=this.id;
                        $(this).trigger("enterKey");
                    }
                });
            }
            function RemoveSection(Row) {
//            alert(Row);
                $('.RemoveRows' + Row).remove();
                $(".AutoCounter").html('');
                var AutoCount = 1;
                $(".AutoCounter").each(function () {
                    AutoCount++;
                    $(this).html(AutoCount);
                });
            }
        </script>
        <script>
            function calc_new(id)
            {
                var total_weight=parseFloat($('#total_weight'+id).val());
                var total_rate_per_weight2=parseFloat($('#total_rate_per_weight'+id).val());
                var total=total_weight*total_rate_per_weight2;
                $('#as_per_weight'+id).val(total);

                var qty=parseFloat($('#system_qty'+id).val());
                var fcy=(total/qty).toFixed(2);

                $('#foreign_currency'+id).val(fcy);

                var total_amount=(qty*fcy).toFixed(2);
                $('#total_amount'+id).val(total_amount);


            }

        </script>

        <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection