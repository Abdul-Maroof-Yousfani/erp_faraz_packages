<?php

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

CommonHelper::companyDatabaseConnection(1);


$master_id=Input::get('master_id');
 $region_id=Input::get('region_id');
$job_order_data = DB::table('job_order_data')->where('job_order_id','=',$master_id)->get();
CommonHelper::reconnectMasterDatabase();

?>

@extends('layouts.default')
@section('content')
    @include('select2')

<?php


?>
<?php echo Form::open(array('url' => 'pad/addJobOrderNextStep','id'=>'JobOrderForm', 'enctype' => 'multipart/form-data')); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Estimate</span>
                </div>
            </div>
            <?php
            $Counter = 1;
            foreach($job_order_data as $Filter):

            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <input type="hidden" name="counter[]" id="" value="<?php echo $Counter; ?>" >
                                <input type="hidden" name="job_order_data_id<?php echo $Counter?>" id="job_order_data_id<?php echo $Counter?>" value="<?php echo $Filter->job_order_data_id?>">

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php
                                    $Product = CommonHelper::get_single_row_prodcut('product','product_id',$Filter->product);
                                    $Region = CommonHelper::get_single_row('region','id',$region_id);
                                        $RegionName = $Region->region_name;
                                        $ProductName = $Product->p_name;
                                    ?>
                                    <label class="sf-label">Product</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="ProductName_<?php echo $Counter?>" id="ProductName_<?php echo $Counter?>" class="form-control" value="<?php echo $ProductName?>" readonly>
                                        <input type="hidden" name="ProductId_<?php echo $Counter?>" id="ProductId_<?php echo $Counter?>" value="<?php echo $Filter->product?>">
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Width <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input type="text" name="Width_<?php echo $Counter;?>" id="Width_<?php echo $Counter;?>" class="form-control" value="<?php echo $Filter->width;?>" readonly>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Height</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" class="form-control requiredField"  name="Height_<?php echo $Counter;?>" id="Height_<?php echo $Counter;?>" value="<?php echo $Filter->height;?>" readonly />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Depth</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" class="form-control requiredField"  name="Depth_<?php echo $Counter;?>" id="Depth_<?php echo $Counter;?>" value="<?php echo $Filter->depth?>" readonly />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="sf-label">Qty</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" class="form-control requiredField"  name="Qty_<?php echo $Counter;?>" id="Qty_<?php echo $Counter;?>" value="<?php echo $Filter->quantity?>" readonly />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    <label>Description</label>
                                    <input type="text" name="Description_<?php echo $Counter;?>" id="Description_<?php echo $Counter;?>" class="form-control" value="<?php echo $Filter->description;?>" readonly>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Region</label>
                                    <input type="text" name="" id="" readonly value="<?php echo $RegionName;?>" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="region_id" value="<?php echo $region_id?>">
                            <div class="lineHeight">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <blockquote style="background-color: black;color: white;width: 30%">
                                       Note: This Stock Showing Region Wise
                                    </blockquote>
                                    <div class="table-responsive">
                                        <div class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                                            <table id="" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">Sr.No <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Item <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Uom <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Stock Value<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Estimate Qty <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody id="AppentTr_<?php echo $Counter;?>">
                                                    <tr class="text-center" id="RemoveTd<?php echo $Counter;?>">
                                                        <td>1</td>
                                                        <td>
                                                            <select onchange="get_data(this.id,'{{$Counter}}')" style="width: 100%" name="item_<?php echo $Counter?>[]" id="item_<?php echo $Counter?>" class="form-control select2">
                                                            <option value="">Select Item</option>
                                                                @foreach(CommonHelper::get_all_subitem() as $row)
                                                                    <option value="{{$row->id}}">{{ucwords($row->sub_ic)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="UomDisp<?php echo $Counter?>[]" id="uom_<?php echo $Counter?>" readonly class="form-control">
                                                            <input type="hidden" name="uom_<?php echo $Counter?>[]" id="uom_<?php echo $Counter?>" readonly class="form-control">

                                                        </td>
                                                        <td><input type="text" name="stock_value_<?php echo $Counter?>[]" id="stock_value_<?php echo $Counter?>" readonly class="form-control"></td>
                                                        <td><input type="number" name="qty_<?php echo $Counter?>[]" id="qty_<?php echo $Counter?>" class="form-control"></td>


                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <button type="button" id="BtnAddMore_<?php echo $Counter?>" class="btn btn-xs btn-success" onclick="AddMoreEstimate('<?php echo $Counter?>')" style="width: 50px">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <?php  $Counter++; endforeach;?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                <!--
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                                <!-->
    </div>
</div>
<?php echo Form::close();?>

<script !src="">


    $(document).ready(function() {

        $('.select2').select2();
    });


    var EstimateCounter = '{{$Counter}}';
    function AddMoreEstimate(SectionCounter)
    {

        $('#AppentTr_'+SectionCounter).append('<tr class="text-center" id="RemoveTd'+EstimateCounter+'">'+
                '<td>'+EstimateCounter+'</td>'+
                '<td><select onchange="get_data(this.id,'+EstimateCounter+')" style="width: 100%" name="item_'+SectionCounter+'[]" id="item_'+EstimateCounter+'" class="form-control select2">'+
                '<option value="">Select Item</option>@foreach(CommonHelper::get_all_subitem() as $row)<option value="{{$row->id}}">{{ucwords($row->sub_ic)}}</option>@endforeach</select>'+
                '</td>'+
                '<td><input type="text" name="uom_'+SectionCounter+'[]" id="uom_'+EstimateCounter+'" readonly class="form-control"></td>'+
                '<td><input type="text" name="stock_value_'+SectionCounter+'[]" id="stock_value_'+EstimateCounter+'" readonly class="form-control"></td>' +
                '<td><input type="number" name="qty_'+SectionCounter+'[]" id="qty_'+EstimateCounter+'" class="form-control"></td>'+
                '<td><button type="button" id="BtnRemove_<?php echo $Counter?>" class="btn btn-xs btn-danger" onclick="RemoveItem('+EstimateCounter+')" style="width: 50px">-</button></td>'+
                '</tr>');
        EstimateCounter++;
        $('.select2').select2();

    }

    function RemoveItem(Count)
    {
            $('#RemoveTd'+Count).remove();

    }

    function get_data(id,counter)
    {
        var item=$('#'+id).val();
        var region='{{$region_id}}';
        $.ajax({
            url : '{{url('/pmfal/get_stock')}}',
            type: 'Get',
            data : {item:item,region:region},
            beforeSend: function()
            {


            },
        }).done(function(response){ //

            var data=response.split(',');

          $('#stock_value_'+counter).val(data[0]);
           $('#uom_'+counter).val(data[1]);
        });

    }

    function get_uom(id,counter)
    {
        var item=$('#'+id).val();
        $.ajax({
            url : '{{url('/pmfal/get_uom')}}',
            type: 'Get',
            data : {item:item},
            beforeSend: function()
            {


            },
        }).done(function(response){ //

            alert(response); return false;
            $('#stock_value_'+counter).val(response);
        });

    }
</script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
