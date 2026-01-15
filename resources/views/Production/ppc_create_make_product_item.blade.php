
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;

?>
<style>
    .modalWidth{
        width: 100%;
    }
</style>

    @include('modal')
    <?php

    ?>

    <div class="">
            <?php echo Form::open(['route' => 'production.make.product', 'method' => 'post', 'id'=>'make-product-form']) ?>
            <input type="hidden" name="work_station_id" value="{{ $workStationId }}">
            <input type="hidden" name="finish_good_type" value="2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive well">
                    @foreach($plan_data as $key => $row)
                    <input type="hidden" name="plan_items[]" value="{{ $key+1 }}">
                    @php
                        $bomInDirect = ProductionHelper::get_bom_for_indirect_workstation($row->finish_goods_id, $workStationId);
                    @endphp
                    <table  class="table table-bordered sf-table-list">
                        <thead>

                        <h5>Finish Good : {{CommonHelper::get_item_name($row->finish_goods_id)}}</h5>
                        <h5>RAW Material</h5>
                        <input type="text" onkeyup="calculateSemiFinishGoodQty({{$key}})" class="production_qty_{{ $key }}" name="production_qty[]" id="production_qty_{{ $key }}">
                        <tr>
                            <th class="text-center" style="width: 30%">Product Name</th>
                            {{-- <th class="text-center" style="">Planned Qty </th> --}}
                            <th class="text-center">Required QTY</th>
                            <th class="text-center">Issue QTY</th>
                            <th class="text-center">Remaining QTY</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center" style="width: 180px;">Workstation</th>

                        </tr>
                        </thead>

                        <tbody>
                        @foreach($bomInDirect as $key2 => $row1)
                        <?php $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->master_id); ?>
                        <?php $issue_balance=ProductionHelper::check_issue_production_floor($row->order_no, $row1->item_id, $workStationId, 1);

                        ?>
                            @if ($row1->type == 1 && $data_count)


                            <?php $uom_name=CommonHelper::get_uom($row1->item_id); ?>
                            <?php $workstation_name=CommonHelper::get_workstation_name($row1->work_station_id); ?>

                            <tr class="text-center" style="">
                                <input type="hidden" name="master_id{{$key}}" value="{{$row->master_id}}"/>
                                <input type="hidden" name="data_bom_data_{{ $key }}_id[]" value="{{ $row1->bom_data_id }}">
                                <input type="hidden" name="data_bom_{{ $key }}_id[]" value="{{ $row1->bom_id }}">
                                <input type="hidden" name="data_item_{{ $key }}_id[]" value="{{ $row1->item_id }}">
                                <input type="hidden" name="data_ppc_no_{{ $key }}[]" value="{{ $row->order_no }}">
                                <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>

                                <input type="hidden" name="data_actual_{{ $key }}_qty[]" value="{{ $row1->qty }}">
                                {{-- <td>{{$row1->qty}}</td> --}}
                                @php
                                    $recipeQt =  ($bomInDirect)? $bomInDirect[0]->bomQty : 1;
                                    $recipeQt =  $recipeQt == 0 ? 1 : $recipeQt;
                                    $getReqQt = ($row1->qty / $recipeQt) *$row->planned_qty;
                                @endphp
                                <input class="data_current_qty_{{ $key }}" type="hidden" name="" value="{{ $row1->qty }}">
                                <input class="data_recipeQt_qty_{{ $key }}" type="hidden" name="" value="{{ $recipeQt }}">
                                <td>
                                    {{-- <input type="hidden" data-current-qty="{{ $row1->qty }}" class="form-control data_request_qty_{{ $key }}" value="{{ $getReqQt }}" type="text" name="data_required_{{ $key }}_qty[]" id="">                                     --}}
                                    <input readonly data-current-qty="{{ $row1->qty }}" class="form-control data_request_qty_{{ $key }}" value="0" type="text" name="data_required_{{ $key }}_qty[]" id="">
                                </td>

                                <input type="hidden" class="data_issue_qty_{{ $key }}" name="data_issue_{{ $key }}_qty[]" value="{{ $issue_balance ?? 0 }}">
                                <td>
                                    {{ $issue_balance ?? '' }}
                                </td>
                                <td>
                                    <input readonly class="form-control data_issue_required_qty_{{ $key }}" type="text" name="data_request_{{ $key }}_qty[]" value="0">
                                </td>

                                <td>{{$uom_name}}</td>

                                <input type="hidden" name="data_work_station_{{ $key }}_id[]" value="{{$row1->work_station_id}}">
                                <td>
                                    {{ $workstation_name }}
                                </td>

                            </tr>

                            @endif
                        @endforeach

                        </tbody>
                    </table>
                    <table  class="table table-bordered sf-table-list">
                        <thead>

                        <h5>WIP GOOD</h5>
                        <tr>
                            <th class="text-center" style="width: 30%">Product Name</th>

                            <th class="text-center">Produced QTY</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center" style="width: 180px;">Location</th>
                            <th class="text-center" style="width: 180px;">Batch Code</th>
                            {{-- <th class="text-center">Available</th> --}}
                            {{-- <th class="text-center"> Issued</th> --}}

                        </tr>
                        </thead>

                        <tbody>
                            @php
                                $index = 1;
                            @endphp
                        @foreach(ProductionHelper::get_bom_for_indirect_extra($row->finish_goods_id, $workStationId) as $row1)

                        @if ($row1->type == 2 || $row1->type == 3)
                        <input type="hidden" name="main_id{{$index}}" value="{{$row->main_id}}" id="main_id{{$index}}"/>
                        <input type="hidden" name="master_id{{$index}}" value="{{$row->master_id}}" id="master_id{{$index}}"/>
                        <input type="hidden" name="type{{$index}}" value="1" id="type{{$index}}"/>
                        <input type="hidden" name="bom_data_id{{$index}}" value="{{$row1->bom_data_id}}" id="bom_data_id{{$index}}"/>
                        <input type="hidden" name="bom_id_{{ $key }}[]" value="{{$row1->bom_id}}" id="bom_id{{$index}}"/>


                            @php
                                $index++;
                            @endphp
                            <?php $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->master_id); ?>
                            <?php $issue_balance=ProductionHelper::check_issue_production_floor($row->order_no, $row1->item_id, $workStationId, 1); ?>
                            <?php $uom_name=CommonHelper::get_uom($row1->item_id); ?>

                            <tr class="text-center" style="">
                                <input type="hidden" name="item_{{ $key }}[]" id="item_id{{$index}}" value="{{$row1->item_id}}"/>
                                <td><input readonly class="form-control" type="text" name="" id="" value="{{CommonHelper::get_item_name($row1->item_id)}}"/> </td>

                                <td>
                                    <input type="hidden" class="form-control wip_product_qty_{{ $key }}" value="{{$row1->qty}}" name="" id="req_qty{{$index}}">
                                    <input readonly type="text" class="form-control wip_product_qty_element_{{ $key }}" value="0" name="req_qty_{{ $key }}[]" id="req_qty{{$index}}">
                                </td>
                                <td>{{$uom_name}}</td>

                                <td>
                                    @if($data_count && $data_count->sum >= $getReqQt ) {{CommonHelper::get_name_warehouse($data_count->warehouse_id)}};
                                    @else
                                        <select  name="location_id_{{ $key }}[]" id="location_id{{$index}}" class="form-control location" onchange="get_detail('item_id{{$index}}','{{$index}}');">
                                            <option value="">Select Location</option>
                                            <?php foreach(CommonHelper::get_all_warehouse() as $warehouse_data):?>
                                            <option
                                                    value="<?php echo $warehouse_data->id?>"><?php echo $warehouse_data->name;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    @endif
                                </td>


                                <td>
                                    <input class="form-control"  name="batch_code_{{ $key }}[]" id="batch_code{{$index}}">
                                </td>
                                {{-- <td><input onblur="issued_qty('{{$index}}')" onkeyup="issued_qty('{{$index}}')"  class="form-control" type="number" readonly id="instock_{{$index}}"/></td> --}}
                                {{-- <td id="td{{$index}}"><button id="btn{{$index}}" disabled onclick="save('{{$index}}')" type="button" class="btn btn-success">Issue</button></td> --}}

                            </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {{ Form::submit('Make Product', ['class' => 'btn btn-success make-product']) }}
            </div>

        <?php echo Form::close();?>
    </div>

    <script>
        $('.make-product').on('click', function(){
            $('#make-product-form').submit();
        });
        function calculateSemiFinishGoodQty(key)
        {
            var production_qty_input = $('.production_qty_'+key).val();
            var data_request_qty = $('.data_request_qty_'+key);
            var wip_product_qty_element = $('.wip_product_qty_element_'+key);
            var wip_product_qty = $('.wip_product_qty_'+key).val();
            var data_issue_qty = $('.data_issue_qty_'+key).val();
            var data_current_qty = $('.data_current_qty_'+key).val();
            var data_recipeQt_qty = $('.data_recipeQt_qty_'+key).val();
            var data_issue_required_qty = $('.data_issue_required_qty_'+key);

            data_request_qty.each(function(index) {
                var newRequiredQty = ($(this).data('current-qty') / data_recipeQt_qty) * production_qty_input

                console.log($('.data_issue_qty_0'));
                if($('.data_issue_qty_0')[index] == undefined){
                    console.log('in1');
                    console.log($('.data_issue_qty_0').val());
                    var new_data_issue_qty = $('.data_issue_qty_0').val()
                }
                else{
                    console.log('in2');
                    var new_data_issue_qty = $('.data_issue_qty_0')[index].value
                }
                var newRequestQty =   new_data_issue_qty - newRequiredQty
                if($('.data_issue_required_qty_0')[index] == undefined){
                    $('.data_issue_required_qty_0').val(newRequestQty);
                }
                else{
                    $('.data_issue_required_qty_0')[index].value = newRequestQty.toFixed(2);
                }
                $(this).val(newRequiredQty.toFixed(2));
                console.log(newRequiredQty);
            });

            // var newRequiredQty = (data_current_qty / data_recipeQt_qty) * production_qty_input
            var newWipProductQty = (wip_product_qty / data_recipeQt_qty) * production_qty_input
            // var newRequestQty = newRequiredQty - data_issue_qty

            // data_request_qty.val(newRequiredQty);
            wip_product_qty_element.val(newWipProductQty.toFixed(2));
            // data_issue_required_qty.val(newRequestQty);


            // if (data_request_qty.val() <= data_issue_qty) {
            //     // console.log(data_request_qty.val(), data_issue_qty);
            //     $(".make-product").attr("disabled", false);
            // } else {
            //     // console.log(data_request_qty.val(), data_issue_qty, 'else');
            //     $(".make-product").attr("disabled", true);
            // }

        }
        function get_detail(id,number)
        {
            //alert(number); return false;


           if (number==1)
           {
               var location_id = $('#location_id'+number).val();
               $('.location').val(location_id);

               $('.location').each(function(i, obj) {
                var   number=$(this).attr('id');
                 number=number.replace("location_id", "");


                   var item=$('#item_id'+number).val();
                   var location_id = $('#location_id'+number).val();
                   if (location_id=='')
                   {
                       $('#batch_code'+number).val('');
                       $('#instock_'+number).val(0);

                   }
                   else
                   {
                       $.ajax({
                           url:'{{url('/pdc/get_batch_code')}}',
                           data:{item:item,location_id:location_id},
                           type:'GET',
                           success:function(response)
                           {
                               //var data=response.split(',');
                               $('#batch_code'+number).html(response);
                               issued_qty(number);
                           }
                       });
                   }

               });
           }
        else
           {
               var item=$('#'+id).val();
               var location_id = $('#location_id'+number).val();
               $.ajax({
                   url:'{{url('/pdc/get_batch_code')}}',
                   data:{item:item,location_id:location_id},
                   type:'GET',
                   success:function(response)
                   {
                       //var data=response.split(',');
                       $('#batch_code'+number).html(response);
                       issued_qty(number);
                   }
               });
           }


        }

        function get_stock_qty(warehouse,number)
        {


            var warehouse=$('#location_id'+number).val();
            var item=$('#item_id'+number).val();
            var batch_code=$('#batch_code'+number).val();



            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data)
                {

                    data=data.split('/');
                    $('#instock_'+number).val(data[0]);

                    if (data[0]==0)
                    {
                        $("#"+item).css("background-color", "red");
                    }
                    else
                    {
                        $("#"+item).css("background-color", "");
                    }
                    issued_qty(number);
                }
            });

        }

        function issued_qty(number)
        {
          var issue_qty=parseFloat($('#issu_qty'+number).val());
          issue_qty= checkNan(issue_qty);
          var avaiable=parseFloat($('#instock_'+number).val());
           console.log(avaiable);
           avaiable=checkNan(avaiable);
            if (issue_qty >avaiable)
            {
                $('#btn'+number).prop("disabled", true);
                $('#issu_qty'+number).css("background-color",'red');
            }
            else
            {
                $('#btn'+number).prop("disabled", false);
                $('#issu_qty'+number).css("background-color",'');
            }
        }

        function save(number)
        {
            $('#btn'+number).prop("disabled", true);
            var main_id=$('#main_id'+number).val();

            var master_id=$('#master_id'+number).val();
            var type=$('#type'+number).val();
            var bom_data_id=$('#bom_data_id'+number).val();
            var issue_qty=$('#issu_qty'+number).val();
            var location_id=$('#location_id'+number).val();
            var batch_code=$('#batch_code'+number).val();
            var item_id=$('#item_id'+number).val();
            var request_qty=$('#req_qty'+number).val();
            $.ajax({
                url:'{{url('/production/save_issue_material')}}',
                data:{main_id:main_id,master_id:master_id,type:type,bom_data_id:bom_data_id,issue_qty:issue_qty,location_id:location_id,batch_code:batch_code,item_id:item_id,request_qty:request_qty},
                type:'GET',
                success:function(response)
                {
                    $('#btn'+number).css("display", 'none');
                    $('#td'+number).html('&#9989;');
                  //  alert('successfully Saved');


                },
                error: function() {
                    $('#td'+number).html('&#x2716;');
                }

            });
        }
    </script>
