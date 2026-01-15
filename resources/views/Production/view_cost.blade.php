<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ProductionHelper;
$so_no='';
$customer='';
if($data->sales_order_id!=''):
    $so_data=SalesHelper::get_sales_tax_by_sales_order_id($data->sales_order_id);
    $so_no=$so_data->so_no;
    $customer=SalesHelper::get_customer_name($so_data->buyers_id);
endif;
?>

<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>
@include('modal')
<form method="post" action="{{ url('production/approve_plan') }}">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
        @if(ProductionHelper::get_approved_plan($data->order_no)==0)
            <button id="decline" onclick="decline_plane('{{$data->id}}',0)" type="button" class="btn btn-danger btn-sm">Decline</button>
            <button id="approve" onclick="" type="submit" class="btn btn-success btn-sm">Approve</button>
            @endif
            <?php ProductionHelper::get_approved_plan($data->order_no); ?>
            @if(ProductionHelper::get_approved_plan($data->order_no)>0)
                <button id="decline" onclick="decline_plane('{{$data->id}}',1)" type="button" class="btn btn-danger btn-sm">Delete</button>
            @endif
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div style="display: none" id="main" class="alert alert-warning">
    <strong>Warning!</strong> Without Issuence Materials, Conversion Not Allowed
</div>
<div class="row main" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">Conversion Cost</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Order Name</td>
                            <td class="text-center"><?php echo strtoupper($data->order_no);?></td>
                        </tr>
                        <tr>
                            <td>Order Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->order_date);?></td>
                        </tr>

                        <tr>
                            <td>Due Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->due_date);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Type</td>
                            <td class="text-center">@if($data->type==1) Standard @else Make To Order @endif</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="text-center">@if($data->status==1) Planned @else Release @endif</td>
                        </tr>

                        <tr>
                            <td>Sales Order</td>
                            <td class="text-center"><?php echo strtoupper($so_no);?></td>
                        </tr>

                        <tr>
                            <td>Customer</td>
                            <td class="text-center"><?php strtoupper($customer) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <input type="hidden" name="production_plan_id" value="{{$data->id}}"/>
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Product</th>
                            <th class="text-center ">Direct Material</th>
                            <th class="text-center">Indirect Material</th>
                            <th class="text-center">Direct Labour</th>
                            <th class="text-center">Die & Mould</th>
                            <th class="text-center">Machine Cost</th>
                            <th class="text-center">FOH</th>
                            <th class="text-center">Total Cost</th>
                            <th class="text-center">Produce QTY</th>
                            <th class="text-center">Produce Cost Per Unit</th>

                            <th class="text-center">Warehouse</th>
                            <th class="text-center">Bacth Code</th>
                         

                        </tr>
                        </thead>
                    <input type="hidden" name="id" value="{{ $data->id }}"/>
                    <input type="hidden" name="order_no" value="{{ $data->order_no }}"/>
                        <tbody>
                            <?php
                            $counter=1;
                            $total_drect_material=0;
                            $total_indirect_material=0;
                            $total_direct_labour=0;
                            $total_die_mould=0;
                            $total_machine_cost=0;
                            $total_fohh=0;
                            $total_cost=0;

                            ?>
                            @foreach($costing_data as $row)

                                <?php

                                $planned_data= ProductionHelper::get_production_plane_detail_data($row->production_plan_data_id);
                                $planned_qty=$planned_data->planned_qty;

                                ?>
                            <tr  class="text-center">
                             <td>{{$counter++}}</td>
                             <td>{{CommonHelper::get_item_name($row->finish_goods_id)}}</td>
                             <td class="cost" style="cursor: pointer" onclick="get_data('direct_material_cost','{{$row->id}}')">{{number_format($row->direct_material_cost,2)}}</td>
                             <td class="cost" style="cursor: pointer" onclick="get_data('indirect_material_cost','{{$row->id}}')">{{number_format($row->indirect_material_cost,2)}}</td>
                             <td class="cost" style="cursor: pointer" onclick="get_data('direct_labour_costing','{{$row->id}}')">{{number_format($row->direct_labour,2)}}</td>
                             <td class="cost" style="cursor: pointer" onclick="get_data('die_mould_costing','{{$row->id}}')">{{number_format($row->die_mould,2)}}</td>
                             <td class="cost"  style="cursor: pointer" onclick="get_data('machine_cost','{{$row->id}}','{{ $planned_qty }}')">{{number_format($row->machine_cost*$planned_qty,2)}}</td>
                             <td class="cost" style="cursor: pointer" onclick="get_data('foh_cost','{{$row->id}}')">{{number_format($row->foh,2)}}</td>
                                <?php $total_foh= $row->direct_material_cost + $row->indirect_material_cost + $row->direct_labour + $row->die_mould +$row->machine_cost*$planned_qty + $row->foh?>
                                <td>{{number_format($total_foh,2)}}</td>

                                <?php
                              $conversion_data=  ProductionHelper::get_conversion_data_row($row->production_plan_data_id);
                               $produce_qty=$conversion_data->produce_qty;
                                ?>
                                <td>{{number_format($produce_qty,2)}}</td>
                                <td>{{number_format($total_foh/$produce_qty,2)}}</td>

                                <td>
                                    @if(ProductionHelper::get_approved_plan($data->order_no)==0)
                                    <select id="location_id{{ $row->id }}" name="warehouse_id[]" class="form-control required_sam">
                                        @foreach(CommonHelper::get_all_warehouse() as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>

                                        @else
                                        <?php echo CommonHelper::get_location($planned_data->warehouse_id);   ?>
                                    @endif

                                </td>
                                <td>
                                    @if(ProductionHelper::get_approved_plan($data->order_no)==0)
                                    <input id="batch_code{{ $row->id }}" type="text" class="form-control required_sam" value="" name="batch_code[]" />

                                        @else
                                        {{ $planned_data->batch_code }}
                                        @endif

                                </td>

                             </tr>


                                <input type="hidden" name="data_id[]" value="{{ $row->production_plan_data_id }}"/>
                            <?php

                            $total_drect_material+=$row->direct_material_cost;
                            $total_indirect_material+= $row->indirect_material_cost;
                            $total_direct_labour+= $row->direct_labour;
                            $total_die_mould+= $row->die_mould;
                            $total_machine_cost+=$row->machine_cost*$planned_qty;
                            $total_fohh+= $row->foh;
                            $total_cost+= $total_foh;

                            ?>

                            @endforeach


                        </tbody>

                        <tr class="text-center" style="font-size: large;font-weight: bold">
                            <td colspan="2">Total</td>
                            <td>{{ number_format($total_drect_material,2) }}</td>
                            <td>{{ number_format($total_indirect_material,2) }}</td>
                            <td>{{ number_format($total_direct_labour,2) }}</td>
                            <td>{{ number_format($total_die_mould,2) }}</td>
                            <td>{{ number_format($total_machine_cost,2) }}</td>
                            <td>{{ number_format($total_fohh,2) }}</td>
                            <td>{{ number_format($total_cost,2) }}</td>
                        </tr>
                    </table>
                        <span id="data">

                              </span>
                </div>
            </div>


            <div class="row">

                 <p><label><input id="vouchers" onclick="get_vouchers('{{$data->order_no}}'),'{{ 17 }}'" type="checkbox">Show Voucher</label></p>

                @include('Production.costing_view.complete_ledger_entries')
                </div>
        </div>
    </div>
</div>
</form>
<script>
    function get_data(cost_page,id,planned_qty=false)
    {

        $('#data').html('<div class="loader">');
            $.ajax({
                url: '{{url('production/get_cost_data')}}',
                type: 'GET',
                data: {id: id,cost_page:cost_page,planned_qty:planned_qty},
                success: function (response)
                {

                    $('#data').html(response);



                }
            });

    }

    function decline_plane(id,type)
    {
        $('#decline').prop('disabled', true);
        $('#data').html('<div class="loader">');
        $.ajax({
            url: '{{url('production/decline_cost')}}',
            type: 'GET',
            data: {id: id,type:type},
            success: function (response)
            {

                $('#showDetailModelOneParamerter').modal('hide');
                location.reload();

            }
        });
    }
    function approve(id,order_no)
    {
        $('#decline').prop('disabled', true);
        $('#data').html('<div class="loader">');
        $.ajax({
            url: '{{url('production/approve_plan')}}',
            type: 'GET',
            data: {id: id , order_no: order_no},
            success: function (response)
            {


               $('#showDetailModelOneParamerter').modal('hide');

            }
        });
    }



    function get_vouchers(voucher_no)
    {

        $('#ledger').css('display','block');

    }


    $("form").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var validate=validate_sam();

        if (validate!=false)
        {

            $("#data").addClass("loader");
            $('.loader').show();
            $('#so_no').prop("disabled", false);
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "GET",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(response)
                {
                    $('#showDetailModelOneParamerter').modal('hide');
                },
                error: function(data, errorThrown)
                {
                    //   $('.hidee').prop('disabled', false);

                }
            });

        }
        else
        {
            e.preventDefault();
        }


    });


    function validate_sam()
    {
        var required = document.getElementsByClassName('required_sam');


        for (i = 0; i < required.length; i++) {
            var rf = required[i].id;


            if ($('#' + rf).val() == '')
            {
                $('#' + rf).css('border-color', 'red');
                $('#' + rf).focus();
                validate = 1;
                alert(rf + ' ' + 'Required');
                event.preventDefault();
                return false;

            }
            else {

                $('#' + rf).css('border-color', '#ccc');
                validate = 0;
            }
        }
    }
</script>
</script>


