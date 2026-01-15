<?php

use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
$EditId = $_GET['edit_id'];
$Master = DB::Connection('mysql2')->table('production_plane')->where('status',1)->where('id',$EditId)->first();
$Detail = DB::Connection('mysql2')->table('production_plane_data')->where('status',1)->where('master_id',$EditId)->get();
        //echo "<pre>";
        //print_r($Detail);
        //die();
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    @include('loader')

    <?php echo Form::open(array('url' => 'prad/update_ppc'));?>

    <div style="display: none" class="" id="main">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Production Plan </span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Order No <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly value="<?php echo $Master->order_no?>" name="order_no" id="order_no" type="text" class="form-control required_sam">
                                        <input type="hidden" name="EditId" value="<?php echo $EditId?>">
                                    </div>



                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Order Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input type="date" value="<?php echo $Master->order_date?>" name="order_date" id="order_date" class="form-control required_sam">
                                    </div>


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Start Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input  required type="date" name="start_date" id="start_date" value="<?php echo $Master->start_date?>" type="text" class="form-control required_sam">
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">End Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input  required type="date" name="end_date" id="end_date" value="<?php echo $Master->end_date?>" type="text" class="form-control required_sam">
                                    </div>

                                </div>




                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Type <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <select onchange="get_type()" name="type" id="type" class="form-control select2 required_sam requiredField" >
                                            <option value="">select</option>
                                            <option  value="1" @if($Master->type == 1) selected @endif >Standard</option>
                                            <option value="2" @if($Master->type == 2) selected @endif >Make To Order</option>
                                        </select>
                                    </div>


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Status <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <select name="status" id="status" class="form-control select2 required_sam requiredField" >

                                            <option value="1">Planned</option>

                                        </select>
                                    </div>


                                    <?php $so_data=DB::Connection('mysql2')->table('sales_order as a')
                                            ->join('customers as b','a.buyers_id','=','b.id')
                                            ->where('a.status',1)
                                            ->select('a.so_no','a.id','b.name','a.buyers_id')
                                            ->get();
                                    ?>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Sales Order <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <select onchange="get_customer_name()"  name="so_no" id="so_no" class="form-control requiredField select2 required_sam" @if($Master->type == 1) disabled @endif >
                                            <option value="0*0*0">Select</option>
                                            @foreach($so_data as $row)
                                                <option value="{{$row->id.'*'.$row->name.'*'.$row->buyers_id}}">{{$row->so_no}}</option>
                                            @endforeach

                                        </select>
                                    </div>


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label  class="sf-label">Customer <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input id="customer_name" name="customer_name" readonly type="text" class="form-control" value=""/>
                                    </div>


                                </div>

                                <div class="lineHeight">&nbsp;&nbsp;</div>

                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Plan Detail (Step 1) </span>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body">

                            <div class="table-responsive" id="data">

                                <table style="width: 80%;margin: auto" class="table table-bordered">
                                    <div class="text-right">
                                        <button type="button" onclick="add_more()" class="btn btn-primary">Add More</button>
                                    </div>
                                    <thead>

                                    <tr>
                                        <th  class="text-center">Sr No</th>
                                        <th style="width: 40%" style="" class="text-center">Product</th>
                                        <th style="width: 40%"  class="text-center">Route</th>
                                        <th style="width: 20%"  class="text-center">Planned Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append">
                                    <?php
                                            $Counter = 1;
                                    foreach($Detail as $Fil):?>
                                    <tr id="tr<?php echo $Counter?>"  class="">

                                        <td class="text-center"><?php echo $Counter?></td>
                                        <td><select onchange="get_route('<?php echo $Counter?>')" class="form-control select2 required_sam product requiredField" name="product[]" id="product<?php echo $Counter?>">
                                                <option value="">select</option>
                                                @foreach($data as $row)
                                                    <option value="{{$row->finish_goods}}" <?php if($Fil->finish_goods_id == $row->finish_goods): echo "selected"; endif;?>>{{CommonHelper::get_item_name($row->finish_goods)}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="detailed_id[]" value="<?php echo $Fil->id?>">
                                        </td>
                                        <td>
                                            <?php $dataRout=DB::Connection('mysql2')->table('production_route')->where('status',1)->where('finish_goods',$Fil->finish_goods_id)->get();?>

                                            <select class="form-control select2 required_sam requiredField" name="route[]" id="route<?php echo $Counter?>">
                                                <option value="">Select Route</option>
                                                <?php  foreach ($dataRout as $row):?>
                                                <option value="<?php echo $row->id ?>" <?php if($Fil->route == $row->id): echo "selected"; endif;?>><?php echo $row->voucher_no ?></option>
                                                <?php endforeach;?>

                                            </select>
                                        </td>

                                        <td><input type="number" id="planned_qty<?php echo $Counter?>" name="planned_qty[]" class="form-control required_sam requiredField" step="any" value="<?php echo $Fil->planned_qty?>"></td>
                                        <?php if($Counter > 1):?>
                                            <td class="text-center"> <input type="button" onclick="remove('<?php echo $Counter?>');IdPushed('<?php echo $Fil->id?>')" value="Remove" class="btn btn-sm btn-danger"> </td>
                                        <?php endif;?>
                                    </tr>
                                    <?php
                                    $Counter++;
                                    endforeach;?>
                                    <input type="hidden" name="DeletedIds" id="DeletedIds" value="">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
        </div>

    </div>

    <?php echo Form::close();?>
    <script>
        $(document).ready(function(){
            $('#product1').select2();
            $('.select2').select2();
            $('#so_no').select2();
            get_type();
        });
        const MultiIds = [];
        function IdPushed(DeletedId)
        {
            MultiIds.push(DeletedId);
            $('#DeletedIds').val(MultiIds);
        }

        var countt='<?php echo $Counter?>';
        function add_more()
        {
            countt++;
            $('#append').append(
                    '<tr class="text-center" id="tr'+countt+'" >' +
                    '<td class="text-center">'+countt+'</td>'+
                    '<td><select style="text-align: left" onchange="get_route('+countt+')" class="form-control select2 product requiredField" name="product[]" id="product'+countt+'"><option value="">select</option>@foreach($data as $row)<option value="{{$row->finish_goods}}">{{CommonHelper::get_item_name($row->finish_goods)}}</option>@endforeach'+
                    '</select>' +
                    '<input type="hidden" name="detailed_id[]" value="0">'+
                    '</td>'+
                    '<td><select class="form-control select2 required_sam requiredField" name="route[]" id="route'+countt+'">'+
                    '<option value="">select</option></select></td>'+
                    '<td><input type="number" id="planned_qty'+countt+'" name="planned_qty[]" class="form-control requiredField" step="any"></td>'+
                    '<td class="text-center"> <input type="button" onclick="remove('+countt+')" value="Remove" class="btn btn-sm btn-danger"> </td>' +
                    '</tr>');


            $('#product'+countt).select2();
        }


        function remove(number)
        {
            $('#tr'+number).remove();
        }

        function get_type()
        {
            var type= $('#type').val();

            if (type==2)
            {
                $('#so_no').prop("disabled", false);
            }
            else
            {
                $('#so_no').prop("disabled", true);
                $('#so_no').prop("disabled", true);
                $('#customer_name').val('');
                $('#so_no').val('0*0*0').trigger('change');

            }
        }

        function get_customer_name()
        {
            var so_no= $('#so_no').val();
            so_no=so_no.split('*');
            $('#customer_name').val(so_no[1]);
        }


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


        $(".product").change(function()
        {



        });

        function get_route(number)
        {
            var product_id=$('#product'+number).val();



            $.ajax({
                type: "GET",
                url: '{{url('production/get_route')}}',
                data: {product_id:product_id}, // serializes the form's elements.
                success: function(response)
                {

                    $('#route'+number).html(response);
                },
                error: function(data, errorThrown)
                {


                }
            });
        }
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection