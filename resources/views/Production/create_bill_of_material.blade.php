<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$m = Session::get('run_company');
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    <style>
        * {
            font-size: 12px!important;
            font-family: Arial;
        }
        .select2 {
            width: 100%;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Bill Of Material </span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'production/insert_bom?m='.$m.'','id'=>'bom_form','class'=>'stop'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Finish Goods<span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <select name="SubItemId" id="SubItemId1" class="form-control select2 requiredField MultiSubItem" >
                                                        <option value="">Select Finish Good</option>
                                                        <?php foreach(CommonHelper::get_finish_goods(1) as $row):
                                                       $count= ProductionHelper::check_product_id('production_bom',$row->id,'finish_goods');
                                                            if ($count==0):
                                                        ?>

                                                        <option value="<?php echo $row->id?>" class="abc EnDis<?php echo $row->id?>"><?php echo $row->sub_ic?></option>
                                                        <?php endif;endforeach;?>
                                                    </select>
                                                </div>




                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Description</label>
                                              
                                                    <textarea  name="Description" id="Description" rows="3" cols="40" style="resize:none;" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#home">Direct Material</a></li>
                                                <li><a data-toggle="tab" href="#menu1">Indrect Material</a></li>

                                            </ul>

                                            <div class="tab-content">
                                                <div id="home" class="tab-pane fade in active">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th colspan="7" class="text-center">BOM Detail</th>
                                                        <th class="text-center">
                                                            <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                                        </th>
                                                        <th class="text-center">
                                                            <span class="badge badge-success" id="span">1</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 30%" class="text-center">Product Name</th>
                                                        <th style="" class="text-center">UOM</th>


                                                        <th style="" class="text-center" >QTY (Per One piece in mm).<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="" class="text-center" >QTY (Per One piece in FT).<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="" class="text-center" >QTY (No Of Piece 20 Feet Length).<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="" class="text-center" >Recoverable Scrap %<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="" class="text-center" >Chips %<span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="" class="text-center" >Turning Scrap %<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="AppnedHtml">
                                                    <tr id="" class="AutoNo">

                                                        <td>
                                                            <input  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_1" placeholder="ITEM">
                                                            <input type="hidden" class="" name="item_id[]" id="sub_1">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" readonly  value="" name="uom[]" id="uom_id1"/>
                                                        </td>


                                                        <td>
                                                            <input step="any" onkeyup="qty_ft(1)" onblur="qty_ft(1)" type="number" class="form-control requiredField" name="qty_mm[]" id="qty1" placeholder="">
                                                        </td>

                                                        <td>
                                                            <input type="number" readonly class="form-control requiredField" name="qty_ft[]" id="qty_ft1" placeholder="">
                                                        </td>

                                                        <td>
                                                            <input type="number" class="form-control requiredField" name="qty_20_length[]" id="qty_20_length1" placeholder="">
                                                        </td>

                                                        <td>
                                                            <input   type="number" value="0" class="form-control requiredField" name="recover_sreacp[]" id="recover_sreacp1" placeholder="">
                                                        </td>

                                                        <td>
                                                            <input onkeyup="tur_sc(1)" onblur="tur_sc(1)" type="number" value="0" class="form-control requiredField" name="recover_chip[]" id="recover_chip1" placeholder="">
                                                        </td>

                                                        <td>
                                                            <input type="number" readonly value="0" class="form-control requiredField" name="turning_scrap[]" id="turning_scrap1" placeholder="">
                                                        </td>



                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>


                                                <div style="" id="menu1" class="tab-pane fade">

                                                            <table id="buildyourform" class="table table-bordered">
                                                                <thead>
                                                                <tr class="text-center">
                                                                    <th colspan="2" class="text-center"><h3></h3> </th>
                                                                    <th colspan="1" class="text-center"><input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More Rows" /></th>
                                                                    <th class="text-center"><span class="badge badge-success" id="span"><?php ?></span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center">Sr No</th>
                                                                    <th class="text-center" style="width: 60% !important;">Item</th>
                                                                    <th class="text-center">Uom<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th class="text-center">Remove</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="" id="AppendHtml">
                                                                <?php
                                                                $Counter = 1;
                                                                ?>
                                                                <tr class="text-center AutoNo">
                                                                    <td><?php echo $Counter;?></td>
                                                                    <td>
                                                                        <input  type="text" class="form-control  sam_jass" name="sub_ic_des[]" id="item_2" placeholder="ITEM">
                                                                        <input type="hidden" class="" name="in_direct_item_id[]" id="sub_2">
                                                                    </td>
                                                                    <td><input class="form-control" type="text" readonly  value="" name="uom[]" id="uom_id2"/></td>
                                                                    <td>
                                                                        <input type="text" class="form-control" id="Qty2" name="Qty[]" placeholder="Quantity"  value="">
                                                                    </td>


                                                                    <td style="background-color: #ccc"></td>

                                                                </tr>
                                                                <?php
                                                                $Counter++;
                                                                ?>

                                                                </tbody>
                                                            </table>

                                                </div>

                                            </div>





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

    <script>




        $(document).ready(function(){
            $('.select2').select2();
        });
        var Counter = 1;
        var countt=2;


        function AddMoreDetails()
        {



            countt++;
            $('#AppnedHtml').append(
                    '<tr id="" class="AutoNo RemoveRows'+countt+'">'+
                    '<td><input  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_'+countt+'" placeholder="ITEM">'+
                    '<input type="hidden" class="" name="item_id[]" id="sub_'+countt+'">'+
                    '</td>'+
                    '<td><input  class="form-control" type="text" readonly  value="" name="uom[]" id="uom_id'+countt+'"/></td>'+
                    '<td><input step="any" onkeyup="qty_ft('+countt+')" onblur="qty_ft('+countt+')" type="number" class="form-control requiredField" name="qty_mm[]" id="qty'+countt+'" placeholder=""></td>'+
                    '<td> <input readonly type="number" class="form-control requiredField" name="qty_ft[]" id="qty_ft'+countt+'" placeholder=""></td>'+
                    '<td> <input type="number" class="form-control requiredField" name="qty_20_length[]" id="qty_20_length'+countt+'" placeholder=""></td>'+
                    '<td><input type="number"   value="0" class="form-control requiredField" name="recover_sreacp[]" id="recover_sreacp'+countt+'" placeholder=""></td>'+
                    '<td><input type="number" onkeyup="tur_sc('+countt+')" onblur="tur_sc('+countt+')" value="0" class="form-control requiredField" name="recover_chip[]" id="recover_chip'+countt+'" placeholder=""></td>'+
                    '<td><input type="number" readonly value="0" class="form-control requiredField" name="turning_scrap[]" id="turning_scrap'+countt+'" placeholder=""></td>'+
                    '<td><button type="button" onclick="RemoveSection('+countt+')" class="btn btn-danger btn-xs">Remove</button></td>'+
                    '</tr>');

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

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

        var x=1;
        function AddMorePvs()
        {
            x++;
            countt++;
            $('#AppendHtml').append('<tr class="text-center AutoNo" id="tr'+countt+'" >' +
                    '<td>'+x+'</td>' +
                    '<td>' +
                    '<textarea  type="text" class="form-control  sam_jass" name="sub_ic_des[]" id="item_'+countt+'" placeholder="ITEM"></textarea>' +
                    '<input type="hidden" class="" name="in_direct_item_id[]" id="sub_'+countt+'" >'+
                    '</td>' +
                    '<td><input readonly="" type="text" class="form-control" name="uom_id[]" id="uom_id'+countt+'"></td>' +
                    '<td>' +
                    '<input type="text" class="form-control " id="Qty'+countt+'" name="Qty[]" placeholder="Quantity">' +
                    '</td>'+
                    '<td class="text-center"> <input type="button" onclick="RemoveRow('+countt+')" value="Remove" class="btn btn-sm btn-danger"> </td>' +
                    '</tr>');
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

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


        function RemoveRow(number)
        {
            $('#tr'+number).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

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
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            var count=1;
            $('.mould').each(function (){

                alert($(this).attr('name'));
                $(this).attr('name', 'MoldId' + count+'[]');
                mould_count++;
            });


            $('.die').each(function (){

                alert($(this).attr('name'));
                $(this).attr('name', 'DaiId' + count+'[]');
                mould_count++;
            });
        }

        function CheckDuplicate()
        {

            var Code = $('#Code').val();
            $.ajax({
                url:'{{url('/production/machineCodeCheck')}}',
                data:{Code:Code},
                type:'GET',
                success:function(response)
                {
                    if(response == 'duplicate')
                    {
                        $('#DuplicateError').html('<span class="text-danger">Batch Code already Exist in (Machine).</span>');
                        $('.btn-success').prop('disabled',false);
                        return false;
                    }
                    else if(response == 'yes')
                    {

                    }
                    else{}
                }
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
                    if(validate == 0)
                    {
                        CheckDuplicate();
                    }
                    else
                    {
                        return false;
                    }
                }

            });
        });




    function qty_ft(number)
    {
        var qty=parseFloat($('#qty'+number).val());
        var total=(qty/304.8).toFixed(2);
        $('#qty_ft'+number).val(total);

    }

    function tur_sc(number)
    {
        var recover_sreacp=parseFloat($('#recover_sreacp'+number).val());

        if (isNaN(recover_sreacp) || recover_sreacp>100)
        {
            recover_sreacp=0;
            $('#recover_sreacp'+number).val(0);
        }
        var recover_chip=parseFloat($('#recover_chip'+number).val());

        if (isNaN(recover_chip) || recover_chip>100)
        {
            recover_chip=0;
            $('#recover_chip'+number).val(0);
        }
        var total=100-recover_chip;
        $('#turning_scrap'+number).val(total);
    }


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

    </script>

    <script>



        function view_history(id)
        {
            var v= $('#sub_item_id_1_'+id).val();

            if ($('#history_1_' + id).is(":checked"))
            {
                if (v!=null)
                {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem?id='+v);
                }
                else
                {
                    alert('Select Item');
                }

            }
        }


    </script>


    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection