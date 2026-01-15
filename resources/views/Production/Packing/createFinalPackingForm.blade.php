@extends('layouts.default')

@section('content')
@include('select2')

<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
$batch_code = ProductionHelper::getNewBatchCode();
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
                                    <form action="{{route('Packing.update')}}" method="post">
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
                                                                    <select name="material_requisition_id" onchange="getMachineProcessDataByMr(event)" class="form-control normal_production select2" id="material_requisition_id">
                                                                        <option value="">Select Production Plan</option>
                                                                        @foreach($general_production_plan as $key => $value)
                                                                            <option value="{{ $value->mr_id }}" data-value="{{ $value->pp_id }}" >
                                                                                {{ $value->order_no.' -- '.$value->finish_good.' -- '.CommonHelper::changeDateFormat($value->order_date) }}
                                                                            </option>
                                                                        @endforeach 
                                                                    </select>
                                                                    <input name="pp_id" id="pp_id" type="hidden" />
                                                                    <input name="mr_id" id="mr_id" type="hidden" />
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
                                                                    <label>Batch Code</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input readonly name="packing_list_no" class="form-control" type="text" value="{{ $batch_code }}">
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
        
    function productionPlanAgainstSo(datas)
    {
        $('#material_requisition_id').empty();
        var id = datas.value;
        $('#more_details').empty();
        $.ajax({
                url: '<?php echo url('/')?>/Production/Packing/productionPlanAndCustomerAgainstSo',
                type: 'Get',
                data: {id:id},
                success: function (data) {
                    
                    var select = document.getElementById("material_requisition_id");

                    // Clear existing options
                    select.innerHTML = "";

                    // Add default option
                    var defaultOption = document.createElement("option");
                    defaultOption.text = "Select Production Plan";
                    defaultOption.value = "";
                    select.appendChild(defaultOption);

                    // Loop through the array and create options
                    data?.material_requisition.forEach(function(mr) {
                        var option = document.createElement("option");
                        option.value = mr.id;
                        option.setAttribute('data-value', mr.pp_id);
                        option.text = mr.order_no + " - " + mr.order_date+ " - " + mr.sub_ic;
                        select.appendChild(option);
                    });

                    var delivery_challan = document.getElementById("delivery_challan_id");
                     // Clear existing options
                     delivery_challan.innerHTML = "";
                     // Add default option
                    var deliverydefaultOption = document.createElement("option");
                    deliverydefaultOption.text = "Select Delivery Challan";
                    deliverydefaultOption.value = "";
                    delivery_challan.appendChild(deliverydefaultOption);

                    // Loop through the array and create options
                    data?.delivery_challan.forEach(function(dc) {
                        var option = document.createElement("option");
                        option.value = dc.id;
                        option.setAttribute('data-qty', dc.qty);
                        option.setAttribute('data-uom', dc.uom_name);
                        option.setAttribute('data-value', dc.sub_ic);
                        option.text = dc.dc_no + " - " + dc.so_no + " ( " +dc.sub_ic + " ) ";
                        
                        delivery_challan.appendChild(option);
                    });

                    $('#customer_id').val(data?.customerDetails?.customer_id)
                    $('#customer_name').val(data?.customerDetails?.name)
                    $('#po_no').val(data?.customerDetails?.purchase_order_no)
                   
             }
            });
    }


    function getMachineProcessDataByMr()
    {

        // let id =  $('#attached_material_requisition_id option:selected').val();
        // let production_attached = $('#production_attached').val();

        var pp_id =  $('#material_requisition_id').val();
        var mr_id =  $('#material_requisition_id option:selected').attr('data-value');
        
        $('#pp_id').val(pp_id);
        $('#mr_id').val(mr_id);


        // if(production_attached == 1) {
        //     if(!id) {
        //         alert('Please Select General Production')
        //         return;
        //     }

        //     var attached_pp_id =  $('#attached_material_requisition_id option:selected').attr('data-value');
        //     $('#attached_pp_id').val(attached_pp_id)
        // }

        // if(production_attached == 0 ) {
        //     $('#attached_pp_id').val(0)
        //     id =  $('#material_requisition_id option:selected').val();
        //     if(!id) {
        //         alert('Please Select Production plan')
        //         return;
        //     }
        // }

        // let range_1 = $('#range_1').val();
        // let range_2 = $('#range_2').val();
        // let dn_qty = $('#delivery_challan_qty').val();

        // document.getElementById("btn").disabled = true;
        // $('#more_details').empty();
        // $('#product_name').val('')
        // $('#product_id').val('')

        if(pp_id) {

            $.ajax({
                url: '<?php echo url('/')?>/Production/Packing/getMachineProcessDataByMr',
                type: 'Get',
                data: { pp_id: pp_id, mr_id: mr_id },  
                success: function (data) {
                    console.log(data)
                    if(data != 0) {
                        $('#more_details').html(data);
                    } else {
                        alert('Data not found')
                    }
                }
            });
        }
    }

    function checkedCheckBox(e)
    {
        let allCheckBox = document.querySelectorAll('.checkbox');
        if(allCheckBox.length > 0)
        {

            if(e.checked)
            {
                document.getElementById("btn").disabled = false;

                allCheckBox.forEach(function(e){
                e.checked = true;
                e.value = 1;    
                })

            }
            else
            {

                document.getElementById("btn").disabled = true;

                allCheckBox.forEach(function(e){
                    e.checked = false
                    e.value = 0;    
                    
                })
            }
        }
        if ($('#delivery_challan_id').val() == '') {
            document.getElementById("btn").disabled = true;
            return;
        }
    }

</script>
@endsection