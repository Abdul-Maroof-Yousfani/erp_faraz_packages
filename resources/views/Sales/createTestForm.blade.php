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
    @include('number_formate')
    <?php



    $data=DB::Connection('mysql2')->table('subitem')
            ->where('sub_ic', 'like', '%' . 'elbow 90d' . '%')->orderBy('item_master_code','ASC')->get()->take(20);
            $VoucherNo = CommonHelper::get_unique_import_no(date('y'),date('m'));

    ?>

    <div class="container-fluid">
        <div class="panel-body">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                @include('Purchase.'.$accType.'purchaseMenu')
            </div>
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
                            <label class="radio-inline"><input onclick="hide_unhide('new','old')" type="radio" name="optradio">New Record</label>
                            <label class="radio-inline"><input onclick="hide_unhide('old','new')" type="radio" name="optradio"> Previous Record</label>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @include('Sales.import_po')
                            </div>
                    <div class="row new" style="display: block">
                        <?php echo Form::open(array('url' => 'sad/addTestForm?m='.$m.'','id'=>'TestingForm','class'=>'stop'));?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">



                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label for="">IGM  No#</label>
                                            <input type="text" class="form-control" id="sys_generate_no" name="sys_generate_no" readonly value="<?php echo $VoucherNo?>">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label for="">IGM Date</label>
                                            <input type="date" class="form-control" id="created_date" name="created_date" value="<?php echo date('Y-m-d')?>">
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label for="">Vendor</label>
                                            <select style="width: 100%" name="supplier_id" id="supplier_id" class="form-control select2 requiredField" required>
                                                <option value="">Select Vendor</option>
                                                <?php foreach($supplier as $Fil):?>
                                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label for="">Document No</label>
                                            <input type="text" name="document_number" id="document_number" class="form-control requiredField">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label for="">Document Date</label>
                                            <input type="date" name="document_date" id="document_date" class="form-control" value="<?php echo date('Y-m-d')?>">
                                        </div>
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <h3 style="text-align: center"> Import By Piece</h3>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive" id="">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 50px" class="text-center">Sr No#</th>
                                                        <th  class="text-center">Item Name</th>
                                                        <th style="width: 100px" class="text-center" >UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 130px" class="text-center"> Qty<span class="rflabelsteric"><strong>*</strong></th>




                                                        <th style="width: 200px;" class="text-center" >FCY Unit Price<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 130px" class="text-center">Total Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        {{--<th style="width: 130px" class="text-center">Grand Total</th>--}}
                                                        <th style="width: 80px" class="text-center">Remove</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="AppnedHtml">

                                                    </tbody>
                                                    <tbody>
                                                    <tr class="text-center" style="font-weight: bold">
                                                        <td  colspan="5" id="">Total</td>
                                                        <td colspan="2" class="" id=""><input readonly type="text" class="number_format form-control" id="piece_total" /> </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>








                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                        </div>
                                    </div>



                                    <div class="row">
                                        <h3 style="text-align: center"> Import By Weight</h3>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive" id="">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 50px" class="text-center">Sr No#</th>
                                                        <th  class="text-center">Item Name</th>
                                                        <th style="width: 100px" class="text-center" >UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 130px" class="text-center"> Qty<span class="rflabelsteric"><strong>*</strong></th>

                                                        <th style="width: 130px" class="text-center"> Total Weight<span class="rflabelsteric"><strong>*</strong></th>
                                                        <th style="width: 130px" class="text-center"> Rate Per Weight<span class="rflabelsteric"><strong>*</strong></th>
                                                        <th style="width: 130px" class="text-center"> Total Amount As Per weight<span class="rflabelsteric"><strong>*</strong></th>


                                                        <th style="width: 200px;" class="text-center" >FCY Unit Price<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 130px" class="text-center">FCY Total Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        {{--<th style="width: 130px" class="text-center">Grand Total</th>--}}
                                                        <th style="width: 80px" class="text-center">Remove</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="append_by_weight">

                                                    </tbody>

                                                    <tbody>
                                                    <tbody>
                                                    <tr class="text-center" style="font-weight: bold">
                                                        <td  colspan="8" id="">Total</td>
                                                        <td colspan="2" class="" id=""><input readonly type="text" class="number_format form-control" id="total_weightt" /> </td>
                                                    </tr>
                                                    </tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <input type="button" class="btn btn-sm btn-primary" onclick="add_more_by_weight()" value="Add More Rows" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="demandsSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

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
                    '<input readonly type="text" class="form-control piece_total" name="total_amount[]" id="total_amount'+Counter+'" placeholder="Total Amount" step="any">' +
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

            total_pie();
            total_weight_section();
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
            $('.number_format').number(true,2);


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
            total_pie();
        }

        function total_pie()
        {
            var total=0;
            $(".piece_total").each(function(){

             total+=+parseFloat($(this).val());
            });

            $('#piece_total').val(total);
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
                    '<input readonly type="text" class="form-control total_weightt" name="wtotal_amount[]" id="total_amount'+Counter+'" placeholder="Total Amount" step="any">' +
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
                total_pie();
                total_weight_section();
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
        total_weight_section();

    }


    function total_weight_section()
    {
        var total=0;
        $(".total_weightt").each(function(){

          var val=  parseFloat($(this).val());
            if (isNaN(val))
            {
                val=0;
            }
            total+=+val;
        });

        $('#total_weightt').val(total);
    }
</script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection