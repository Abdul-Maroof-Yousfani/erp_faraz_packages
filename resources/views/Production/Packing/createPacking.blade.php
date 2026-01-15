@extends('layouts.default')

@section('content')
@include('select2')

<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
$PKG=CommonHelper::generateUniquePosNoWithStatusOne('packings','packing_list_no','PKG');
?>
<style>
    .my-lab label {
    padding-top:0px; 
}
</style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <form action="{{route('Packing.store')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Add Packing</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>Production Request</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select onchange="getPurchaseRequestData(this.value)" name="pr_id"
                                                                        id="pr_id" class="form-control select2 requiredField">
                                                                        <option value="">Select Production Request</option>
                                                                        @foreach (ProductionHelper::getAllProductionRequests() as $work)
                                                                            <option value="{{$work->id}}">{{$work->pr_no}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input readonly name="pp_id" id="pp_id"  class="form-control" type="hidden">
                                                                    <input readonly name="attached_pp_id" id="attached_pp_id"  class="form-control" type="hidden" value="0">
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="form-group" >
                                                                <div class="col-md-4" style="margin-top: 8px">
                                                                    <label>Finish Good</label>
                                                                </div>
                                                                <div class="col-sm-8" style="margin-top: 8px">
                                                                    <select onchange="work_change(this.value)" name="pr_data_id"
                                                                        id="pr_data_id" class="form-control select2 requiredField">
                                                                        <option value="">Select Finish Good</option>
                                                                     
                                                                    </select>
                                                                    <input readonly name="pp_id" id="pp_id"  class="form-control" type="hidden">
                                                                    <input readonly name="attached_pp_id" id="attached_pp_id"  class="form-control" type="hidden" value="0">
                                                                    
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-sm-4" style="margin-top: 8px">Packing Date</label>
                                                                <div class="col-sm-8" style="margin-top: 8px">
                                                                    <input name="packing_date" value="{{date('Y-m-d')}}" class="form-control" type="date">
                                                                </div>
                                                            </div>
                                                          
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>Packing List No</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input readonly name="packing_list_no" class="form-control" type="text" value="{{ $PKG }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group hide">
                                                                <div class="col-md-4">
                                                                    <label>Product Name</label>
                                                                    <!-- <label>Purchase Request No.</label> -->
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input readonly name="product_name" id="product_name" value="" class="form-control" type="text">
                                                                    <input readonly name="product_id" id="product_id" value="" class="form-control" type="hidden">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group hide">
                                                                {{-- <div class="col-md-4">
                                                                    <label>bundle range</label>
                                                                    <!-- <label>Purchase Request No.</label> -->
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input id="range_1" class="form-control" type="text">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input id="range_2" class="form-control" type="text">
                                                                </div> --}}
                                                                <div class="col-md-4">
                                                                    <label>Delivery Challan Quantity</label>
                                                                    <!-- <label>Purchase Request No.</label> -->
                                                                </div>
                                                                <div class="col-sm-3 hide">
                                                                    <input  id="delivery_challan_product" class="form-control" type="text" readonly>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input id="delivery_challan_qty" class="form-control" type="text" readonly>
                                                                </div> 
                                                                <div class="col-sm-3">
                                                                    <input id="delivery_challan_uom" class="form-control" type="text" readonly>
                                                                </div> 

                                                                <a onclick="getMachineProcessDataByMr()" class="btn btn-primary mr-1"> Search</a>
                                                            </div>
                                                            
                                                        </div>

                                                        <div class="col-md-4 hide">
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <label for="production_attached" >general production attached to production </label>
                                                                
                                                                    <input type="checkbox" name="production_attached" id="production_attached" onclick="productionFlag(event)" value="0">

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>General Production Plan</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select disabled name="attached_material_requisition_id" onchange="changeProduction(event)"  class="form-control select2 general_production" id="attached_material_requisition_id">
                                                                        <option value="">select General Production Plan</option>
                                                                        @foreach($general_production_plan as $key => $value)
                                                                            <option value="{{ $value->mr_id }}" data-value="{{ $value->pp_id }}" >{{ $value->order_no.' -- '.$value->order_date }}</option>
                                                                        @endforeach 
                                                                    </select>

                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padt">
                                                        <div class="col-md-12 padt">
                                                            <div class="col-md-12" id="more_details">
                                                            
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 padtb text-right">
                                                        <div class="col-md-9"></div>    
                                                        <div class="col-md-3 my-lab">
                                                            <button type="submit" class="btn btn-primary mr-1" id="btn" >Save</button>
                                                        </div>    
                                                    </div>
                                                </div>        
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>

    $('.select2').select2();

    function getPurchaseRequestData(value) {
        if (value) {
            $.ajax({
                url: '<?php echo url('/')?>/Production/Packing/getPurchaseRequestData',
                type: 'GET',
                data: { id: value },
                success: function (data) {
                    $('#pr_data_id').empty().append('<option value="">Select Finish Good</option>');

                    $.each(data, function(index, item) {
                        $('#pr_data_id').append(
                            `<option value="${item.id}">${item.item_code} - ${item.sub_ic} (${item.pack_size} ${item.uom})</option>`
                        );
                    });

                    $('#pr_data_id').select2();
                }
            });
        }
    }

    function work_change(value) {
        let pr_data_id = value;
        var pr_id = $('#pr_id').val();
        if (pr_data_id) {
            $.ajax({
                url: '<?php echo url('/')?>/Production/Packing/createPackingFormAjax',
                type: 'Get',
                data: { pr_id: pr_id, pr_data_id: pr_data_id },
                success: function (data) {

                    $('#more_details').html(data);
                    var order_no = $('#order_no').val();
                    var customer_name = $('#customer_name').val();
                    $('#pr_no').val(order_no);
                }
            });

        }
        else {
            $('#more_details').html('');
            $('#so_number').val('');
            $('#customer').val('');
        }
    }

</script>

@endsection