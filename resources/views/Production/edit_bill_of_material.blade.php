<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
$EditId = $_GET['edit_id'];
$Bom = DB::Connection('mysql2')->table('production_bom')->where('id',$EditId)->first();
$BomDirect = DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('master_id',$EditId)->get();
$BomInDirect = DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('main_id',$EditId)->get();


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

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Bill Of Material </span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'production/update_bom?m='.$m.'','id'=>'bom_form','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="EditId" value="<?php echo $EditId?>">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Finish Goods. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select name="SubItemId" id="SubItemId1" class="form-control select2 requiredField MultiSubItem" >
                                                    <option value="">Select Finish Good</option>
                                                    <?php foreach(CommonHelper::get_finish_goods(1) as $Fil):?>
                                                    <option value="<?php echo $Fil->id?>" class="abc EnDis<?php echo $Fil->id?>" <?php if($Bom->finish_goods == $Fil->id): echo "selected"; endif;?>><?php echo $Fil->sub_ic?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Created Date</label>
                                                <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                <input readonly  type="text" class="form-control requiredField" placeholder="" name="created_date" id="created_date"
                                                       value="<?php echo $Bom->date?>" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Created Users</label>
                                                <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                <input readonly  type="text" class="form-control requiredField" placeholder="" name="username" id="username" value="{{ucfirst($Bom->username)}}" />
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea  name="Description" id="Description" rows="3" cols="40" style="resize:none;" class="form-control requiredField"><?php echo $Bom->description?></textarea>
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
                                                        <th style="width: 30%" class="text-center">item</th>
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
                                                    <?php
                                                            $DirectCounter = 1;
                                                    foreach($BomDirect as $Direct):?>
                                                    <tr id="" class="AutoNo">

                                                        <td>
                                                            <input  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_<?php echo $DirectCounter;?>" placeholder="ITEM" value="<?php echo CommonHelper::get_item_name($Direct->item_id    )?>">
                                                            <input type="hidden" class="" name="item_id[]" id="sub_<?php echo $DirectCounter?>" value="<?php echo $Direct->item_id?>">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" readonly  value="<?php echo CommonHelper::only_uom_nam_by_item_id($Direct->item_id)?>" name="uom[]" id="uom_id<?php echo $DirectCounter?>"/>
                                                        </td>


                                                        <td>
                                                            <input onkeyup="qty_ft('<?php echo $DirectCounter?>')" onblur="qty_ft('<?php echo $DirectCounter?>')" type="number" class="form-control requiredField" name="qty_mm[]" id="qty<?php echo $DirectCounter?>" placeholder="" value="<?php echo $Direct->qty_mm?>" step="any">
                                                        </td>

                                                        <td>
                                                            <input type="number" readonly class="form-control requiredField" name="qty_ft[]" id="qty_ft<?php echo $DirectCounter?>" placeholder="" value="<?php echo $Direct->qty_ft?>" step="any">
                                                        </td>

                                                        <td>
                                                            <input type="number" class="form-control requiredField" name="qty_20_length[]" id="qty_20_length<?php echo $DirectCounter?>" placeholder="" value="<?php echo $Direct->qty_20_length?>" step="any">
                                                        </td>

                                                        <td>
                                                            <input type="number" value="<?php echo $Direct->recover_sreacp?>" class="form-control requiredField" name="recover_sreacp[]" id="recover_sreacp<?php echo $DirectCounter?>" placeholder="">
                                                        </td>

                                                        <td>
                                                            <input onkeyup="tur_sc('<?php echo $DirectCounter?>')" onblur="tur_sc('<?php echo $DirectCounter?>')" type="number" value="<?php echo $Direct->recover_chip?>" class="form-control requiredField" name="recover_chip[]" id="recover_chip<?php echo $DirectCounter?>" placeholder="" step="any">
                                                        </td>

                                                        <td>
                                                            <input type="number" readonly value="<?php echo $Direct->turning_scrap?>" class="form-control requiredField" name="turning_scrap[]" id="turning_scrap<?php echo $DirectCounter?>" placeholder="" step="any">
                                                        </td>
                                                        <input type="hidden" name="direct_data_id[]" value="{{ $Direct->id }}"/>
                                                    </tr>
                                                    <?php
                                                    $DirectCounter++;
                                                    endforeach;
                                                    ?>
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
                                                    $InDirectCounter = $DirectCounter;
                                                    foreach($BomInDirect as $InDirect):
                                                    ?>
                                                    <tr class="text-center AutoNo">
                                                        <td><?php echo $InDirectCounter;?></td>
                                                        <td>
                                                            <input  type="text" class="form-control  sam_jass" name="sub_ic_des[]" id="item_<?php echo $InDirectCounter?>" placeholder="ITEM" value="<?php echo CommonHelper::get_item_name($InDirect->item_id)?>">
                                                            <input type="hidden" class="" name="in_direct_item_id[]" id="sub_<?php echo $InDirectCounter?>" value="<?php echo $InDirect->item_id?>">
                                                        </td>
                                                        <td><input class="form-control" type="text" readonly  value="<?php echo CommonHelper::only_uom_nam_by_item_id($InDirect->item_id)?>" name="uom[]" id="uom_id<?php echo $InDirectCounter?>"/></td>
                                                        <td>
                                                            <input type="text" class="form-control" id="Qty<?php echo $InDirectCounter?>" name="Qty[]" placeholder="Quantity"  value="<?php echo $InDirect->qty?>" step="any">
                                                        </td>


                                                        <td style="background-color: #ccc"></td>
                                                        <input type="hidden" name="indirect_data_id[]" value="{{ $InDirect->id }}"/>
                                                    </tr>
                                                    <?php
                                                    $InDirectCounter++;
                                                            endforeach;
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

    <script>




        $(document).ready(function(){
            $('.select2').select2();
        });
        var Counter = 1;
        var countt='<?php echo $DirectCounter?>';


        function AddMoreDetails()
        {



            countt++;
            $('#AppnedHtml').append(
                    '<tr id="" class="AutoNo RemoveRows'+countt+'">'+
                    '<td><input  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_'+countt+'" placeholder="ITEM">'+
                    '<input type="hidden" class="" name="item_id[]" id="sub_'+countt+'">'+
                    '</td>'+
                    '<td><input  class="form-control" type="text" readonly  value="" name="uom[]" id="uom_id'+countt+'"/></td>'+
                    '<td><input onkeyup="qty_ft('+countt+')" onblur="qty_ft('+countt+')" type="number" class="form-control requiredField" name="qty_mm[]" id="qty'+countt+'" placeholder=""></td>'+
                    '<td> <input readonly type="number" class="form-control requiredField" name="qty_ft[]" id="qty_ft'+countt+'" placeholder=""></td>'+
                    '<td> <input type="number" class="form-control requiredField" name="qty_20_length[]" id="qty_20_length'+countt+'" placeholder=""></td>'+
                    '<td><input type="number"   value="0" class="form-control requiredField" name="recover_sreacp[]" id="recover_sreacp'+countt+'" placeholder=""></td>'+
                    '<td><input type="number" onkeyup="tur_sc('+countt+')" onblur="tur_sc('+countt+')" value="0" class="form-control requiredField" name="recover_chip[]" id="recover_chip'+countt+'" placeholder=""></td>'+
                    '<td><input type="number" readonly value="0" class="form-control requiredField" name="turning_scrap[]" id="turning_scrap'+countt+'" placeholder=""></td>'+
                    '<td><button type="button" onclick="RemoveSection('+countt+')" class="btn btn-danger btn-xs">Remove</button></td>'+
                    '</tr><input type="hidden" name="direct_data_id[]" value="0"/>');

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
                    '</tr><input type="hidden" name="indirect_data_id[]" value="0"/>');
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